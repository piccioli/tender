#!/bin/bash

# Script per pulire cache Laravel/Nova tramite Docker

CONTAINER_NAME=$(docker compose ps -q app)

echo "Pulizia cache Laravel/Nova nel container $CONTAINER_NAME..."

docker compose exec app php artisan view:clear

docker compose exec app php artisan cache:clear

docker compose exec app php artisan config:clear

docker compose exec app php artisan nova:cache

echo "Pulizia completata." 