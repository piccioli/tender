#!/bin/bash

# Script per riavviare tutti i servizi Docker
echo "🔄 Riavvio servizi Docker..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Ferma i servizi
echo "🛑 Fermata servizi..."
docker-compose down

# Avvia i servizi
echo "🚀 Avvio servizi..."
docker-compose up -d

if [ $? -eq 0 ]; then
    echo "✅ Servizi riavviati con successo!"
    echo ""
    echo "📊 Stato container:"
    docker-compose ps
else
    echo "❌ Errore nel riavvio dei servizi"
    exit 1
fi
