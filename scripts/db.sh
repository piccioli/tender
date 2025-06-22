#!/bin/bash

# Script per accedere al database
echo "🗄️  Accesso al database..."

# Verifica che docker-compose.yml esista
if [ ! -f "docker-compose.yml" ]; then
    echo "❌ Errore: docker-compose.yml non trovato"
    echo "   Assicurati di essere nella directory del progetto"
    exit 1
fi

# Determina il tipo di database dal docker-compose.yml
if grep -q "mysql:" docker-compose.yml; then
    echo "🔗 Connessione al database MySQL 'laravel'..."
    echo "   Utente: laravel"
    echo "   Password: password"
    echo "   Database: laravel"
    echo ""
    # Accede al database MySQL
    docker-compose exec mysql mysql -u laravel -ppassword laravel
else
    echo "🔗 Connessione al database PostgreSQL 'laravel'..."
    echo "   Utente: laravel"
    echo "   Password: password"
    echo "   Database: laravel"
    echo ""
    # Accede al database PostgreSQL
    docker-compose exec postgres psql -U laravel -d laravel
fi
