#!/bin/bash

# Script per creare una nuova pagina Nova
# Uso: ./scripts/create_nova_page.sh nome-pagina

set -e  # Exit on any error

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funzione per stampare messaggi colorati
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Controllo parametri
if [ $# -eq 0 ]; then
    print_error "Devi specificare il nome della pagina!"
    echo "Uso: $0 nome-pagina"
    echo "Esempio: $0 test-page"
    exit 1
fi

PAGE_NAME=$1

# Validazione nome pagina (kebab-case)
if [[ ! $PAGE_NAME =~ ^[a-z][a-z0-9-]*[a-z0-9]$ ]]; then
    print_error "Il nome della pagina deve essere in kebab-case (es: test-page, my-custom-page)"
    exit 1
fi

# Controllo se Docker è in esecuzione
if ! docker info > /dev/null 2>&1; then
    print_error "Docker non è in esecuzione. Avvia Docker e riprova."
    exit 1
fi

print_message "Creazione pagina Nova: $PAGE_NAME"

# Esegui il comando Artisan
print_step "Esecuzione comando Artisan..."
docker-compose exec app php artisan ms:create-nova-page $PAGE_NAME

if [ $? -eq 0 ]; then
    print_message "✅ Pagina Nova '$PAGE_NAME' creata con successo!"
else
    print_error "❌ Errore durante la creazione della pagina Nova"
    exit 1
fi 