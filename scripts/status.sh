#!/bin/bash

# Script per visualizzare lo stato dei servizi Docker
echo "📊 Stato servizi Docker..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Mostra lo stato dei container
echo "🐳 Container:"
docker-compose ps

echo ""
echo "🌐 Network:"
docker network ls | grep $(basename $(pwd))

echo ""
echo "💾 Volumi:"
docker volume ls | grep $(basename $(pwd))
