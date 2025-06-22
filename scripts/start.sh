#!/bin/bash

# Script per avviare tutti i servizi Docker
echo "🚀 Avvio servizi Docker per il progetto Laravel..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Avvia tutti i servizi
echo "🐳 Avvio container..."
docker-compose up -d

if [ $? -eq 0 ]; then
    echo "✅ Servizi avviati con successo!"
    echo ""
    echo "📋 Informazioni utili:"
    echo "   - Web server: http://localhost:8000"
    
    # Determina il tipo di database dal docker-compose.yml
    if grep -q "mysql:" docker-compose.yml; then
        echo "   - Database MySQL: localhost:3306"
        echo "   - Credenziali DB: laravel/password"
    else
        echo "   - Database PostgreSQL: localhost:5432"
        echo "   - Credenziali DB: laravel/password"
    fi
    echo ""
    echo "📊 Stato container:"
    docker-compose ps
else
    echo "❌ Errore nell'avvio dei servizi"
    exit 1
fi
