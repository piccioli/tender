#!/bin/bash

# Script per configurare l'ambiente locale
# Questo script crea il link simbolico tra .env.local e .env

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

# Controllo se siamo nella directory corretta
if [ ! -f "docker-compose.yml" ]; then
    error "Script deve essere eseguito dalla directory root del progetto (dove si trova docker-compose.yml)"
fi

log "🔧 Configurazione ambiente locale..."

# Controllo se esiste il file .env.local
if [ ! -f ".env.local" ]; then
    error "❌ File .env.local non trovato! Crea prima il file .env.local con la configurazione locale."
fi

# Controllo se esiste già un file .env
if [ -f ".env" ]; then
    # Controllo se è già un link simbolico
    if [ -L ".env" ]; then
        log "✅ Link simbolico .env già esistente"
        
        # Controllo se punta a .env.local
        if [ "$(readlink .env)" = ".env.local" ]; then
            log "✅ Link simbolico già configurato correttamente"
        else
            warn "⚠️  Link simbolico .env punta a $(readlink .env), non a .env.local"
            read -p "Vuoi ricreare il link simbolico? (y/N): " -n 1 -r
            echo ""
            if [[ $REPLY =~ ^[Yy]$ ]]; then
                rm .env
                ln -s .env.local .env
                log "✅ Link simbolico ricreato"
            fi
        fi
    else
        warn "⚠️  File .env esistente ma non è un link simbolico"
        read -p "Vuoi creare un backup e sostituirlo con un link simbolico? (y/N): " -n 1 -r
        echo ""
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            BACKUP_DATE=$(date +'%Y%m%d_%H%M%S')
            BACKUP_FILE=".env.backup.${BACKUP_DATE}"
            cp .env "$BACKUP_FILE"
            log "✅ Backup del file .env creato: $BACKUP_FILE"
            
            rm .env
            ln -s .env.local .env
            log "✅ Link simbolico creato"
        else
            log "Operazione annullata"
            exit 0
        fi
    fi
else
    # Creo il link simbolico
    ln -s .env.local .env
    log "✅ Link simbolico .env -> .env.local creato"
fi

log "🎉 Configurazione ambiente locale completata!"
info "📝 Ora puoi modificare .env.local per cambiare la configurazione locale"
info "🔗 Il file .env punterà sempre a .env.local" 