#!/bin/bash

# Script per visualizzare i log dei servizi Docker
echo "📋 Log dei servizi Docker..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Mostra i log di tutti i servizi
docker-compose logs -f
