#!/bin/bash

# Script per accedere alla shell del container app
echo "🐚 Accesso alla shell del container app..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Accede alla shell del container app
docker-compose exec app bash
