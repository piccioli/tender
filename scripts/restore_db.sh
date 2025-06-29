#!/bin/bash
set -e

BACKUP_DIR="$(dirname "$0")/../db_backups"
DB_CONTAINER="tender_postgres"
DB_NAME=${DB_DATABASE:-laravel}
DB_USER=${DB_USERNAME:-laravel}
DB_PASS=${DB_PASSWORD:-password}

# Configurazione per il download dalla produzione
PROD_HOST=${PROD_HOST:-"http://46.62.139.115/"}
PROD_USER=${PROD_USER:-"root"}
PROD_BACKUP_PATH=${PROD_BACKUP_PATH:-"/root/tender/db_backups"}
PROD_SSH_KEY=${PROD_SSH_KEY:-"~/.ssh/id_rsa"}

# Funzione per mostrare l'help
show_help() {
    cat <<EOM
Uso: $0 [OPZIONI]

Opzioni:
    --download    Scarica il backup dalla produzione e lo usa per il restore
    --help        Mostra questo messaggio di aiuto

Se non viene specificata l'opzione --download, lo script userà il file ms_last.sql
dalla directory locale dei backup.

Variabili d'ambiente per il download:
    PROD_HOST         Host della produzione (default: your-production-host.com)
    PROD_USER         Utente SSH per la produzione (default: deploy)
    PROD_BACKUP_PATH  Percorso dei backup sulla produzione (default: /var/www/tender/db_backups)
    PROD_SSH_KEY      Chiave SSH per la connessione (default: ~/.ssh/id_rsa)

Esempi:
    $0 --download                    # Scarica e usa il backup dalla produzione
    $0                               # Usa il file ms_last.sql locale
EOM
}

# Parsing degli argomenti
DOWNLOAD_MODE=false
while [[ $# -gt 0 ]]; do
    case $1 in
        --download)
            DOWNLOAD_MODE=true
            shift
            ;;
        --help)
            show_help
            exit 0
            ;;
        *)
            echo "Opzione sconosciuta: $1"
            show_help
            exit 1
            ;;
    esac
done

# Funzione per scaricare il backup dalla produzione
download_production_backup() {
    echo "🔍 Connessione alla produzione per scaricare il backup..."
    
    # Verifica che la chiave SSH esista
    if [ ! -f "$PROD_SSH_KEY" ]; then
        echo "❌ Chiave SSH non trovata: $PROD_SSH_KEY"
        echo "   Imposta la variabile PROD_SSH_KEY o crea la chiave SSH"
        exit 1
    fi
    
    # Crea la directory dei backup se non esiste
    mkdir -p "$BACKUP_DIR"
    
    # Nome fisso del backup in produzione
    PROD_BACKUP_FILE="ms_last.sql.tgz"
    PROD_BACKUP_FULL_PATH="$PROD_BACKUP_PATH/$PROD_BACKUP_FILE"
    
    echo "📋 Verifica esistenza del backup: $PROD_BACKUP_FULL_PATH"
    
    # Verifica che il file esista sulla produzione
    if ! ssh -i "$PROD_SSH_KEY" -o ConnectTimeout=10 "$PROD_USER@$PROD_HOST" "[ -f $PROD_BACKUP_FULL_PATH ]"; then
        echo "❌ File di backup non trovato sulla produzione: $PROD_BACKUP_FULL_PATH"
        exit 1
    fi
    
    echo "📥 Download del backup: $PROD_BACKUP_FILE"
    
    # Scarica il backup compresso
    scp -i "$PROD_SSH_KEY" "$PROD_USER@$PROD_HOST:$PROD_BACKUP_FULL_PATH" "$BACKUP_DIR/"
    
    echo "📦 Decompressione del backup..."
    
    # Decomprimi il file .tgz
    tar -xzf "$BACKUP_DIR/$PROD_BACKUP_FILE" -C "$BACKUP_DIR"
    
    # Rinomina il file decompresso
    mv "$BACKUP_DIR/ms_last.sql" "$BACKUP_DIR/ms_last_prod.sql"
    BACKUP_FILE="ms_last_prod.sql"
    
    echo "✅ Backup scaricato e decompresso: $BACKUP_FILE"
}

# Funzione per verificare il backup locale
check_local_backup() {
    BACKUP_FILE="ms_last.sql"
    BACKUP_PATH="$BACKUP_DIR/$BACKUP_FILE"
    
    if [ ! -f "$BACKUP_PATH" ]; then
        echo "❌ File di backup non trovato: $BACKUP_PATH"
        echo "   Assicurati che il file ms_last.sql sia presente nella directory $BACKUP_DIR"
        exit 1
    fi
    
    echo "✅ Utilizzo backup locale: $BACKUP_FILE"
}

# Logica principale
if [ "$DOWNLOAD_MODE" = true ]; then
    download_production_backup
else
    check_local_backup
fi

# 2. Conferma
cat <<EOM

ATTENZIONE: Questa operazione eliminerà TUTTI I DATI ATTUALI del database "$DB_NAME" e li sostituirà con quelli del backup "$BACKUP_FILE".

!!! QUESTA OPERAZIONE È IRREVERSIBILE !!!

Scrivi "CONFERMO" per procedere:
EOM
read -r CONFIRM
if [ "$CONFIRM" != "CONFERMO" ]; then
    echo "Operazione annullata."
    exit 1
fi

# 3. Svuota il database e rilancia le migration
echo "🗑️  Svuotamento del database..."
SCRIPT_DIR="$(dirname "$0")"
$SCRIPT_DIR/artisan.sh db:wipe --force
$SCRIPT_DIR/artisan.sh migrate --force

# 4. Restore tramite docker
echo "🔄 Ripristino del database..."
cat "$BACKUP_DIR/$BACKUP_FILE" | docker exec -i -e PGPASSWORD="$DB_PASS" $DB_CONTAINER psql -U "$DB_USER" "$DB_NAME"

echo "✅ Restore completato dal file: $BACKUP_FILE" 