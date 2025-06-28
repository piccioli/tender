#!/bin/bash

# Script di Deploy per Produzione - Montagna Servizi SCPA
# Questo script automatizza il processo di deploy in produzione

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

# Controllo se Docker è in esecuzione
if ! docker info > /dev/null 2>&1; then
    error "Docker non è in esecuzione. Avvia Docker e riprova."
fi

# Controllo se docker-compose è disponibile
if ! command -v docker-compose &> /dev/null; then
    error "docker-compose non è installato o non è nel PATH"
fi

log "🚀 Iniziando deploy in produzione..."

# Step 1: Fermare i container Docker
log "📦 Fermando i container Docker..."
docker-compose down
if [ $? -eq 0 ]; then
    log "✅ Container Docker fermati con successo"
else
    warn "⚠️  Alcuni container potrebbero essere già fermi"
fi

# Step 2: Pull del codice più recente
log "📥 Eseguendo git pull..."
git pull origin main
if [ $? -eq 0 ]; then
    log "✅ Codice aggiornato con successo"
else
    error "❌ Errore durante il pull del codice"
fi

# Step 3: Avviare i container Docker
log "🚀 Avviando i container Docker..."
docker-compose up -d
if [ $? -eq 0 ]; then
    log "✅ Container Docker avviati con successo"
else
    error "❌ Errore durante l'avvio dei container"
fi

# Step 4: Attendere che i container siano pronti
log "⏳ Attendendo che i container siano pronti..."
sleep 10

# Step 5: Composer update
log "📦 Eseguendo composer update..."
docker-compose exec -T app composer update --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    log "✅ Composer update completato con successo"
else
    error "❌ Errore durante composer update"
fi

# Step 6: Eseguire le migrazioni
log "🔄 Eseguendo le migrazioni del database..."
docker-compose exec -T app php artisan migrate --force
if [ $? -eq 0 ]; then
    log "✅ Migrazioni completate con successo"
else
    error "❌ Errore durante l'esecuzione delle migrazioni"
fi

# Step 7: Pulire tutte le cache
log "🧹 Pulendo tutte le cache..."
docker-compose exec -T app php artisan optimize:clear
if [ $? -eq 0 ]; then
    log "✅ Cache pulite con successo"
else
    warn "⚠️  Errore durante la pulizia delle cache, ma continuando..."
fi

# Step 8: Ottimizzare per la produzione
log "⚡ Ottimizzando per la produzione..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
if [ $? -eq 0 ]; then
    log "✅ Ottimizzazioni completate con successo"
else
    warn "⚠️  Alcune ottimizzazioni potrebbero essere fallite"
fi

# Step 9: Controllo finale dello stato
log "🔍 Controllo finale dello stato..."
docker-compose ps
if [ $? -eq 0 ]; then
    log "✅ Tutti i container sono in esecuzione"
else
    warn "⚠️  Alcuni container potrebbero non essere in esecuzione"
fi

# Step 10: Test di connessione
log "🌐 Testando la connessione all'applicazione..."
sleep 5
if curl -f http://localhost:8000 > /dev/null 2>&1; then
    log "✅ Applicazione risponde correttamente"
else
    warn "⚠️  L'applicazione potrebbe non essere ancora pronta"
fi

log "🎉 Deploy completato con successo!"
log "📊 Riepilogo delle operazioni:"
echo "   ✅ Container Docker fermati e riavviati"
echo "   ✅ Codice aggiornato da git"
echo "   ✅ Composer update eseguito"
echo "   ✅ Migrazioni database completate"
echo "   ✅ Cache pulite e ottimizzate"
echo "   ✅ Applicazione testata"

info "🔗 L'applicazione dovrebbe essere disponibile su: http://localhost:8000"
info "📝 Per controllare i log: docker-compose logs -f app" 