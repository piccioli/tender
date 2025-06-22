#!/bin/bash

# Script per eseguire comandi Composer
echo "📦 Esecuzione comando Composer..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Verifica che sia fornito un comando
if [ $# -eq 0 ]; then
    echo "❌ Errore: Devi specificare un comando Composer"
    echo "Uso: ./scripts/composer.sh <comando>"
    echo "Esempi:"
    echo "   ./scripts/composer.sh install"
    echo "   ./scripts/composer.sh require package/name"
    echo "   ./scripts/composer.sh update"
    exit 1
fi

# Esegue il comando Composer nel container app
docker-compose exec app composer "$@"
