#!/bin/bash
set -e

BACKUP_DIR="$(dirname "$0")/../db_backups"
DB_CONTAINER="tender_postgres"
DB_NAME=${DB_DATABASE:-laravel}
DB_USER=${DB_USERNAME:-laravel}
DB_PASS=${DB_PASSWORD:-password}

# 1. Lista file backup
if [ ! -d "$BACKUP_DIR" ]; then
  echo "Directory dei backup non trovata: $BACKUP_DIR"
  exit 1
fi

cd "$BACKUP_DIR"
BACKUPS=( *.sql )
if [ ${#BACKUPS[@]} -eq 0 ]; then
  echo "Nessun file di backup trovato in $BACKUP_DIR"
  exit 1
fi

echo "Backup disponibili:"
select BACKUP_FILE in "${BACKUPS[@]}"; do
  if [ -n "$BACKUP_FILE" ]; then
    break
  fi
done

cd - > /dev/null

# 2. Conferma
cat <<EOM

ATTENZIONE: Questa operazione eliminerà TUTTI I DATI ATTUALI del database "$DB_NAME" e li sostituirà con quelli del backup "$BACKUP_FILE".

!!! QUESTA OPERAZIONE È IRREVERSIBILE !!!

Scrivi "CONFERMO" per procedere:
EOM
read -r CONFIRM
if [ "$CONFIRM" != "CONFERMO" ]; then
  echo "Operazione annullata."
  exit 1
fi

# 3. Svuota il database e rilancia le migration
SCRIPT_DIR="$(dirname "$0")"
$SCRIPT_DIR/artisan.sh db:wipe --force
$SCRIPT_DIR/artisan.sh migrate --force

# 4. Restore tramite docker
cat "$BACKUP_DIR/$BACKUP_FILE" | docker exec -i -e PGPASSWORD="$DB_PASS" $DB_CONTAINER psql -U "$DB_USER" "$DB_NAME"

echo "Restore completato dal file: $BACKUP_FILE" 