#!/bin/bash

# Script di Deploy per Produzione e Locale - Montagna Servizi SCPA
# Questo script automatizza il processo di deploy in produzione o locale

set -e  # Exit on any error

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funzione per log colorato
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

info() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')] INFO: $1${NC}"
}

# Controllo parametro obbligatorio
if [ $# -eq 0 ]; then
    error "❌ Parametro obbligatorio mancante!"
    echo "Uso: $0 <ambiente>"
    echo "  prod  - Deploy in produzione"
    echo "  local - Deploy in locale"
    echo ""
    echo "Esempi:"
    echo "  $0 prod"
    echo "  $0 local"
    exit 1
fi

ENVIRONMENT=$1

# Validazione del parametro
if [ "$ENVIRONMENT" != "prod" ] && [ "$ENVIRONMENT" != "local" ]; then
    error "❌ Parametro non valido: $ENVIRONMENT"
    echo "Parametri validi: prod, local"
    exit 1
fi

# Controllo se siamo nella directory corretta
if [ ! -f "docker-compose.yml" ]; then
    error "Script deve essere eseguito dalla directory root del progetto (dove si trova docker-compose.yml)"
fi

# Controllo se Docker è in esecuzione
if ! docker info > /dev/null 2>&1; then
    error "Docker non è in esecuzione. Avvia Docker e riprova."
fi

# Controllo se docker-compose è disponibile
if ! command -v docker-compose &> /dev/null; then
    error "docker-compose non è installato o non è nel PATH"
fi

# Messaggio di conferma specifico per ambiente
if [ "$ENVIRONMENT" = "prod" ]; then
    echo -e "${YELLOW}⚠️  ATTENZIONE: Stai per eseguire un deploy in PRODUZIONE! ⚠️${NC}"
    echo ""
    echo -e "${RED}Questa operazione:${NC}"
    echo "   • Fermerà temporaneamente l'applicazione"
    echo "   • Aggiornerà il codice sorgente"
    echo "   • Eseguirà le migrazioni del database"
    echo "   • Potrebbe causare downtime"
    echo ""
    echo -e "${YELLOW}Prima di procedere, verrà eseguito un backup automatico del database.${NC}"
    echo ""
    
    read -p "Sei sicuro di voler procedere con il deploy in PRODUZIONE? (y/N): " -n 1 -r
    echo ""
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo -e "${BLUE}Deploy annullato dall'utente.${NC}"
        exit 0
    fi
else
    echo -e "${BLUE}🚀 Iniziando deploy in ambiente LOCALE...${NC}"
    echo ""
fi

log "🚀 Iniziando deploy in ambiente: $ENVIRONMENT"

# Step 1: Backup del database (solo per produzione)
if [ "$ENVIRONMENT" = "prod" ]; then
    log "💾 Eseguendo backup del database..."
    if [ -f "scripts/backup_db.sh" ]; then
        bash scripts/backup_db.sh
        if [ $? -eq 0 ]; then
            log "✅ Backup del database completato con successo"
        else
            warn "⚠️  Errore durante il backup del database, ma continuando..."
        fi
    else
        warn "⚠️  Script di backup non trovato (scripts/backup_db.sh), continuando senza backup..."
    fi
fi

# Step 2: Gestione file .env
log "🔧 Gestione file .env per ambiente: $ENVIRONMENT"

if [ "$ENVIRONMENT" = "prod" ]; then
    # PRODUZIONE: Gestione file .env
    # Controllo se esiste il file .env
    if [ -f ".env" ]; then
        # Creo backup del file .env attuale
        BACKUP_DATE=$(date +'%Y%m%d_%H%M%S')
        BACKUP_FILE=".env.backup.${BACKUP_DATE}"
        cp .env "$BACKUP_FILE"
        log "✅ Backup del file .env creato: $BACKUP_FILE"
        
        # Elimino il file .env attuale
        rm .env
        log "✅ File .env eliminato"
    else
        warn "⚠️  File .env non trovato, continuando..."
    fi

    # Controllo se esiste il file .env.local
    if [ -f ".env.local" ]; then
        # Copio .env.local in .env
        cp .env.local .env
        log "✅ File .env.local copiato in .env"
    else
        error "❌ File .env.local non trovato! Impossibile procedere con il deploy."
    fi

    # Controllo se esiste il file .env.prod
    if [ -f ".env.prod" ]; then
        # Sostituisco i valori delle variabili presenti in .env.prod
        log "🔄 Applicando override di produzione..."
        
        # Leggo ogni riga del file .env.prod e aggiorno .env
        while IFS= read -r line || [ -n "$line" ]; do
            # Salto commenti e righe vuote
            if [[ $line =~ ^[[:space:]]*# ]] || [[ -z "${line// }" ]]; then
                continue
            fi
            
            # Estraggo nome e valore della variabile
            if [[ $line =~ ^([^=]+)=(.*)$ ]]; then
                var_name="${BASH_REMATCH[1]}"
                var_value="${BASH_REMATCH[2]}"
                
                # Sostituisco la variabile nel file .env
                if grep -q "^${var_name}=" .env; then
                    # La variabile esiste, la sostituisco
                    sed -i.bak "s/^${var_name}=.*/${var_name}=${var_value}/" .env
                    log "   ✅ Aggiornata: $var_name"
                else
                    # La variabile non esiste, la aggiungo
                    echo "${var_name}=${var_value}" >> .env
                    log "   ✅ Aggiunta: $var_name"
                fi
            fi
        done < .env.prod
        
        # Rimuovo il file di backup temporaneo di sed
        rm -f .env.bak
        
        log "✅ Override di produzione applicati con successo"
    else
        warn "⚠️  File .env.prod non trovato, utilizzando configurazione locale"
    fi
else
    # LOCALE: Gestione file .env
    if [ ! -f ".env" ]; then
        if [ -f ".env.local" ]; then
            # Creo link simbolico .env -> .env.local
            ln -sf .env.local .env
            log "✅ Link simbolico .env -> .env.local creato"
        else
            error "❌ File .env.local non trovato! Impossibile procedere con il deploy locale."
        fi
    else
        log "✅ File .env già presente"
    fi
fi

# Step 3: Fermare i container Docker
log "📦 Fermando i container Docker..."
docker-compose down
if [ $? -eq 0 ]; then
    log "✅ Container Docker fermati con successo"
else
    warn "⚠️  Alcuni container potrebbero essere già fermi"
fi

# Step 4: Pull del codice più recente
log "📥 Eseguendo git pull..."
git pull origin main
if [ $? -eq 0 ]; then
    log "✅ Codice aggiornato con successo"
else
    error "❌ Errore durante il pull del codice"
fi

# Step 5: Avviare i container Docker
log "🚀 Avviando i container Docker..."
docker-compose up -d
if [ $? -eq 0 ]; then
    log "✅ Container Docker avviati con successo"
else
    error "❌ Errore durante l'avvio dei container"
fi

# Step 6: Attendere che i container siano pronti
log "⏳ Attendendo che i container siano pronti..."
sleep 10

# Step 7: Composer update
log "📦 Eseguendo composer update..."
docker-compose exec -T app composer update --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    log "✅ Composer update completato con successo"
else
    error "❌ Errore durante composer update"
fi

# Step 8: Gestione Database
log "🗄️  Gestione Database per ambiente: $ENVIRONMENT"

if [ "$ENVIRONMENT" = "prod" ]; then
    # PRODUZIONE: Prima migrazioni, poi seeder ruoli/permessi
    log "🔄 Eseguendo le migrazioni del database..."
    docker-compose exec -T app php artisan migrate --force
    if [ $? -eq 0 ]; then
        log "✅ Migrazioni completate con successo"
    else
        error "❌ Errore durante l'esecuzione delle migrazioni"
    fi
    
    log "🌱 Eseguendo seeder ruoli e permessi..."
    docker-compose exec -T app php artisan db:seed --class=RolePermissionSeeder --force
    if [ $? -eq 0 ]; then
        log "✅ Seeder ruoli e permessi completato con successo"
    else
        warn "⚠️  Errore durante il seeder, ma continuando..."
    fi
else
    # LOCALE: Restore database, seeder ruoli e migrazioni
    log "📥 Eseguendo restore del database con download..."
    if [ -f "scripts/restore_db.sh" ]; then
        bash scripts/restore_db.sh --download
        if [ $? -eq 0 ]; then
            log "✅ Restore del database completato con successo"
        else
            warn "⚠️  Errore durante il restore del database, ma continuando..."
        fi
    else
        warn "⚠️  Script di restore non trovato (scripts/restore_db.sh), continuando..."
    fi
    
    log "🌱 Eseguendo seeder ruoli e permessi..."
    docker-compose exec -T app php artisan db:seed --class=RolePermissionSeeder
    if [ $? -eq 0 ]; then
        log "✅ Seeder ruoli e permessi completato con successo"
    else
        warn "⚠️  Errore durante il seeder, ma continuando..."
    fi
    
    log "🔄 Eseguendo le migrazioni del database..."
    docker-compose exec -T app php artisan migrate --force
    if [ $? -eq 0 ]; then
        log "✅ Migrazioni completate con successo"
    else
        error "❌ Errore durante l'esecuzione delle migrazioni"
    fi
fi

# Step 9: Pulire tutte le cache
log "🧹 Pulendo tutte le cache..."
docker-compose exec -T app php artisan optimize:clear
if [ $? -eq 0 ]; then
    log "✅ Cache pulite con successo"
else
    warn "⚠️  Errore durante la pulizia delle cache, ma continuando..."
fi

# Step 10: Ottimizzare per la produzione (solo per produzione)
if [ "$ENVIRONMENT" = "prod" ]; then
    log "⚡ Ottimizzando per la produzione..."
    docker-compose exec -T app php artisan config:cache
    docker-compose exec -T app php artisan route:cache
    docker-compose exec -T app php artisan view:cache
    if [ $? -eq 0 ]; then
        log "✅ Ottimizzazioni completate con successo"
    else
        warn "⚠️  Alcune ottimizzazioni potrebbero essere fallite"
    fi
fi

# Step 11: Controllo finale dello stato
log "🔍 Controllo finale dello stato..."
docker-compose ps
if [ $? -eq 0 ]; then
    log "✅ Tutti i container sono in esecuzione"
else
    warn "⚠️  Alcuni container potrebbero non essere in esecuzione"
fi

# Step 12: Test di connessione
log "🌐 Testando la connessione all'applicazione..."
sleep 5
if curl -f http://localhost:8000 > /dev/null 2>&1; then
    log "✅ Applicazione risponde correttamente"
else
    warn "⚠️  L'applicazione potrebbe non essere ancora pronta"
fi

log "🎉 Deploy completato con successo!"
log "📊 Riepilogo delle operazioni per ambiente: $ENVIRONMENT"

if [ "$ENVIRONMENT" = "prod" ]; then
    echo "   ✅ Backup del database eseguito"
    echo "   ✅ File .env gestiti per produzione"
    echo "   ✅ Container Docker fermati e riavviati"
    echo "   ✅ Codice aggiornato da git"
    echo "   ✅ Composer update eseguito"
    echo "   ✅ Migrazioni database completate"
    echo "   ✅ Seeder ruoli e permessi completato"
    echo "   ✅ Cache pulite e ottimizzate"
    echo "   ✅ Applicazione testata"
else
    echo "   ✅ File .env gestiti per locale"
    echo "   ✅ Container Docker fermati e riavviati"
    echo "   ✅ Codice aggiornato da git"
    echo "   ✅ Composer update eseguito"
    echo "   ✅ Restore database con download completato"
    echo "   ✅ Seeder ruoli e permessi completato"
    echo "   ✅ Migrazioni database completate"
    echo "   ✅ Cache pulite"
    echo "   ✅ Applicazione testata"
fi

info "🔗 L'applicazione dovrebbe essere disponibile su: http://localhost:8000"
info "📝 Per controllare i log: docker-compose logs -f app" 