#!/bin/bash

# Script per creare una nuova pagina Nova
# Uso: ./scripts/create_nova_page.sh nome-pagina

set -e  # Exit on any error

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funzione per stampare messaggi colorati
print_message() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

print_step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Controllo parametri
if [ $# -eq 0 ]; then
    print_error "Devi specificare il nome della pagina!"
    echo "Uso: $0 nome-pagina"
    echo "Esempio: $0 test-page"
    exit 1
fi

PAGE_NAME=$1

# Validazione nome pagina (kebab-case)
if [[ ! $PAGE_NAME =~ ^[a-z][a-z0-9-]*[a-z0-9]$ ]]; then
    print_error "Il nome della pagina deve essere in kebab-case (es: test-page, my-custom-page)"
    exit 1
fi

# Controllo se il componente esiste già
if [ -d "nova-components/$PAGE_NAME" ]; then
    print_error "Il componente Nova '$PAGE_NAME' esiste già!"
    exit 1
fi

# Controllo se Docker è in esecuzione
if ! docker info > /dev/null 2>&1; then
    print_error "Docker non è in esecuzione. Avvia Docker e riprova."
    exit 1
fi

print_message "Creazione pagina Nova: $PAGE_NAME"

# Step 1: Crea il Tool
print_step "1. Creazione Tool Nova..."
docker-compose exec app php artisan nova:tool tender/$PAGE_NAME

# Step 2: Registra il Tool in Nova
print_step "2. Registrazione Tool in NovaServiceProvider..."

# Crea il nome della classe in PascalCase
CLASS_NAME=$(echo $PAGE_NAME | sed -r 's/(^|-)([a-z])/\U\2/g')

# Backup del file originale
cp app/Providers/NovaServiceProvider.php app/Providers/NovaServiceProvider.php.backup

# Aggiungi l'import
sed -i.bak "/use Tender\\\WelcomePage\\\WelcomePage;/a\\
use Tender\\\${CLASS_NAME^}\\\${CLASS_NAME^};" app/Providers/NovaServiceProvider.php

# Aggiungi il tool nell'array
sed -i.bak "/new WelcomePage,/a\\
            new ${CLASS_NAME^}()," app/Providers/NovaServiceProvider.php

# Rimuovi i file di backup temporanei
rm app/Providers/NovaServiceProvider.php.bak

# Step 3: Configura la route
print_step "3. Configurazione route in ToolServiceProvider..."

TOOL_PROVIDER_PATH="nova-components/$PAGE_NAME/src/ToolServiceProvider.php"

# Backup del file originale
cp "$TOOL_PROVIDER_PATH" "$TOOL_PROVIDER_PATH.backup"

# Sostituisci il metodo routes
sed -i.bak 's|Nova::router.*|Nova::router(['\''nova'\'', '\''nova.auth'\'', Authorize::class], '\'''\'')\
        ->group(__DIR__.'\''/../routes/inertia.php'\'');|' "$TOOL_PROVIDER_PATH"

# Rimuovi il file di backup temporaneo
rm "$TOOL_PROVIDER_PATH.backup"

# Step 4: Definisci la rotta Inertia
print_step "4. Creazione route Inertia..."

INERTIA_ROUTE_PATH="nova-components/$PAGE_NAME/routes/inertia.php"

# Backup del file originale
cp "$INERTIA_ROUTE_PATH" "$INERTIA_ROUTE_PATH.backup"

# Sostituisci il contenuto del file
cat > "$INERTIA_ROUTE_PATH" << EOF
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/$PAGE_NAME', function () {
    return Inertia::render('Tool');
});
EOF

# Step 5: Crea il contenuto della pagina
print_step "5. Creazione contenuto pagina Vue..."

VUE_PAGE_PATH="nova-components/$PAGE_NAME/resources/js/pages/Tool.vue"

# Crea il contenuto della pagina Vue
cat > "$VUE_PAGE_PATH" << EOF
<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold">$PAGE_NAME</h1>
    <p>Benvenuto nella tua pagina personalizzata di Nova.</p>
  </div>
</template>

<script>
export default {
  name: 'Tool',
}
</script>
EOF

# Step 6: Compila gli asset
print_step "6. Compilazione asset..."

cd "nova-components/$PAGE_NAME"

# Installa dipendenze
print_message "Installazione dipendenze npm..."
docker-compose exec app sh -c "cd nova-components/$PAGE_NAME && npm install"

# Compila asset
print_message "Compilazione asset..."
docker-compose exec app sh -c "cd nova-components/$PAGE_NAME && npm run dev"

cd ../..

# Step 7: Svuota la cache delle rotte
print_step "7. Pulizia cache rotte..."
docker-compose exec app php artisan route:clear

print_message "✅ Pagina Nova '$PAGE_NAME' creata con successo!"
print_message "🌐 La pagina è accessibile da: /nova/$PAGE_NAME"
print_message "📁 Componente creato in: nova-components/$PAGE_NAME"

# Mostra informazioni aggiuntive
echo ""
print_message "📋 Riepilogo modifiche:"
echo "   - Tool Nova creato: tender/$PAGE_NAME"
echo "   - Registrato in: app/Providers/NovaServiceProvider.php"
echo "   - Route configurata: /nova/$PAGE_NAME"
echo "   - Pagina Vue creata: nova-components/$PAGE_NAME/resources/js/pages/Tool.vue"
echo "   - Asset compilati in: nova-components/$PAGE_NAME/dist/"
echo ""
print_message "💡 Per personalizzare la pagina, modifica: nova-components/$PAGE_NAME/resources/js/pages/Tool.vue" 