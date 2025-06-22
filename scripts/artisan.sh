#!/bin/bash

# Script per eseguire comandi Artisan Laravel
echo "⚙️  Esecuzione comando Artisan..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Verifica che sia fornito un comando
if [ $# -eq 0 ]; then
    echo "❌ Errore: Devi specificare un comando Artisan"
    echo "Uso: ./scripts/artisan.sh <comando>"
    echo "Esempi:"
    echo "   ./scripts/artisan.sh migrate"
    echo "   ./scripts/artisan.sh make:controller UserController"
    echo "   ./scripts/artisan.sh list"
    exit 1
fi

# Esegue il comando Artisan nel container app
docker-compose exec app php artisan "$@"
