#!/bin/bash
set -e

BACKUP_DIR="$(dirname "$0")/../db_backups"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
FILENAME="tender_backup_$TIMESTAMP.sql"

# Crea la cartella se non esiste
mkdir -p "$BACKUP_DIR"

# Parametri di connessione (modifica se necessario)
DB_CONTAINER="tender_postgres"
DB_NAME=${DB_DATABASE:-laravel}
DB_USER=${DB_USERNAME:-laravel}
DB_PASS=${DB_PASSWORD:-password}

# Esegui il dump tramite docker
docker exec -e PGPASSWORD="$DB_PASS" $DB_CONTAINER pg_dump --data-only -U "$DB_USER" "$DB_NAME" > "$BACKUP_DIR/$FILENAME"

echo "Backup eseguito: $BACKUP_DIR/$FILENAME" 