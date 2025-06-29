#!/bin/bash
set -e

BACKUP_DIR="$(dirname "$0")/../db_backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
FILENAME="ms_backup_$TIMESTAMP.sql"
COMPRESSED_FILENAME="ms_backup_$TIMESTAMP.sql.tgz"
LATEST_LINK="ms_last.sql.tgz"

# Crea la cartella se non esiste
mkdir -p "$BACKUP_DIR"

# Parametri di connessione (modifica se necessario)
DB_CONTAINER="tender_postgres"
DB_NAME=${DB_DATABASE:-laravel}
DB_USER=${DB_USERNAME:-laravel}
DB_PASS=${DB_PASSWORD:-password}

echo "Esecuzione backup del database..."

# Esegui il dump tramite docker
docker exec -e PGPASSWORD="$DB_PASS" $DB_CONTAINER pg_dump --data-only -U "$DB_USER" "$DB_NAME" > "$BACKUP_DIR/$FILENAME"

echo "Backup SQL creato: $BACKUP_DIR/$FILENAME"

# Comprimi il file di backup
echo "Compressione del file di backup..."
tar -czf "$BACKUP_DIR/$COMPRESSED_FILENAME" -C "$BACKUP_DIR" "$FILENAME"

# Rimuovi il file SQL non compresso
rm "$BACKUP_DIR/$FILENAME"

echo "Backup compresso creato: $BACKUP_DIR/$COMPRESSED_FILENAME"

# Crea/aggiorna il link simbolico al backup più recente
if [ -L "$BACKUP_DIR/$LATEST_LINK" ]; then
    rm "$BACKUP_DIR/$LATEST_LINK"
fi
ln -s "$COMPRESSED_FILENAME" "$BACKUP_DIR/$LATEST_LINK"

echo "Link simbolico aggiornato: $BACKUP_DIR/$LATEST_LINK -> $COMPRESSED_FILENAME"

# Elimina i backup più vecchi di 7 giorni
echo "Pulizia dei backup vecchi (più di 7 giorni)..."
find "$BACKUP_DIR" -name "ms_backup_*.sql.tgz" -type f -mtime +7 -delete

# Conta quanti file sono stati eliminati (opzionale)
DELETED_COUNT=$(find "$BACKUP_DIR" -name "ms_backup_*.sql.tgz" -type f -mtime +7 | wc -l)
if [ "$DELETED_COUNT" -gt 0 ]; then
    echo "Eliminati $DELETED_COUNT backup vecchi"
else
    echo "Nessun backup vecchio da eliminare"
fi

echo "Backup completato con successo!" 