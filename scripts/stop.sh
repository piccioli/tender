#!/bin/bash

# Script per fermare tutti i servizi Docker
echo "🛑 Fermata servizi Docker..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Ferma tutti i servizi
docker-compose down

if [ $? -eq 0 ]; then
    echo "✅ Servizi fermati con successo!"
else
    echo "❌ Errore nella fermata dei servizi"
    exit 1
fi
