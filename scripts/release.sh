#!/bin/bash

# Script di Release - Montagna Servizi SCPA
# Questo script gestisce la creazione di nuove release con versioning semantico

set -e  # Exit on any error

# Colori per output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Funzione per log colorato
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

warn() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}"
    exit 1
}

info() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')] INFO: $1${NC}"
}

# Funzione per mostrare l'help
show_help() {
    echo -e "${BLUE}Script di Release - Gestione Versioni Semantiche${NC}"
    echo ""
    echo -e "${YELLOW}Uso:${NC}"
    echo "  ./scripts/release.sh <tipo> [release_base]"
    echo ""
    echo -e "${YELLOW}Tipi di release:${NC}"
    echo "  major     - Incrementa la versione major (es: 1.0.0 -> 2.0.0)"
    echo "  minor     - Incrementa la versione minor (es: 1.0.0 -> 1.1.0)"
    echo "  hotfix    - Incrementa la versione patch (es: 1.0.0 -> 1.0.1)"
    echo ""
    echo -e "${YELLOW}Parametri:${NC}"
    echo "  tipo        - Tipo di release (obbligatorio): major, minor, hotfix"
    echo "  release_base - Per hotfix: versione base (es: 1.0.0)"
    echo ""
    echo -e "${YELLOW}Esempi:${NC}"
    echo "  ./scripts/release.sh major"
    echo "  ./scripts/release.sh minor"
    echo "  ./scripts/release.sh hotfix 1.0.0"
    echo ""
    echo -e "${YELLOW}Note:${NC}"
    echo "  - Per hotfix è obbligatorio specificare la release base"
    echo "  - Lo script recupera automaticamente l'ultima versione da git"
    echo "  - Richiede conferma prima di procedere con la release"
}

# Funzione per validare il formato della versione
validate_version() {
    local version=$1
    if [[ ! $version =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
        error "Formato versione non valido: $version. Deve essere nel formato X.Y.Z"
    fi
}

# Funzione per recuperare l'ultima versione da git
get_last_version() {
    local last_tag=$(git describe --tags --abbrev=0 2>/dev/null || echo "0.0.0")
    echo $last_tag
}

# Funzione per costruire la nuova versione
build_new_version() {
    local current_version=$1
    local release_type=$2
    local hotfix_base=$3
    
    # Rimuovi il prefisso 'v' se presente
    current_version=${current_version#v}
    
    # Per hotfix, usa la versione base specificata
    if [ "$release_type" = "hotfix" ]; then
        if [ -z "$hotfix_base" ]; then
            error "Per hotfix è obbligatorio specificare la release base"
        fi
        validate_version "$hotfix_base"
        current_version=$hotfix_base
    else
        validate_version "$current_version"
    fi
    
    # Parsing della versione
    IFS='.' read -r major minor patch <<< "$current_version"
    
    case $release_type in
        "major")
            major=$((major + 1))
            minor=0
            patch=0
            ;;
        "minor")
            minor=$((minor + 1))
            patch=0
            ;;
        "hotfix")
            patch=$((patch + 1))
            ;;
        *)
            error "Tipo di release non valido: $release_type. Usa: major, minor, hotfix"
            ;;
    esac
    
    echo "${major}.${minor}.${patch}"
}

# Funzione per aggiornare APP_VERSION nel file .env.local
update_env_version() {
    local new_version=$1
    local release_date=$(date +'%Y-%m-%d')
    local env_file=".env.local"
    
    log "📝 Aggiornando APP_VERSION in $env_file..."
    
    # Verifica se il file .env.local esiste
    if [ ! -f "$env_file" ]; then
        warn "⚠️  File $env_file non trovato, creando nuovo file..."
        touch "$env_file"
    fi
    
    # Crea la nuova stringa APP_VERSION con versione e data
    local new_app_version="APP_VERSION='$new_version ($release_date)'"
    
    # Verifica se APP_VERSION esiste già nel file
    if grep -q "^APP_VERSION=" "$env_file"; then
        # Aggiorna la variabile esistente
        if [[ "$OSTYPE" == "darwin"* ]]; then
            # macOS
            sed -i '' "s/^APP_VERSION=.*/$new_app_version/" "$env_file"
        else
            # Linux
            sed -i "s/^APP_VERSION=.*/$new_app_version/" "$env_file"
        fi
        log "✅ APP_VERSION aggiornata: $new_app_version"
    else
        # Aggiungi la nuova variabile alla fine del file
        echo "" >> "$env_file"
        echo "$new_app_version" >> "$env_file"
        log "✅ APP_VERSION aggiunta: $new_app_version"
    fi
    
    info "📋 APP_VERSION aggiornata in $env_file"
}

# Funzione per creare la release
create_release() {
    local new_version=$1
    local release_type=$2
    
    log "🚀 Creando release $new_version ($release_type)..."
    
    # Step 1: Verifica che non ci siano modifiche non committate
    if [ -n "$(git status --porcelain)" ]; then
        warn "⚠️  Ci sono modifiche non committate nel repository"
        echo "Modifiche trovate:"
        git status --short
        echo ""
        read -p "Vuoi continuare comunque? (y/N): " -n 1 -r
        echo ""
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            error "Release annullata. Committa le modifiche prima di procedere."
        fi
    fi
    
    # Step 2: Verifica che siamo sul branch main
    local current_branch=$(git branch --show-current)
    if [ "$current_branch" != "main" ]; then
        warn "⚠️  Non sei sul branch main (attuale: $current_branch)"
        read -p "Vuoi continuare comunque? (y/N): " -n 1 -r
        echo ""
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            error "Release annullata. Passa al branch main prima di procedere."
        fi
    fi
    
    # Step 3: Pull delle ultime modifiche
    log "📥 Aggiornando repository..."
    git pull origin main
    
    # Step 4: Aggiornare APP_VERSION nel file .env.local
    update_env_version "$new_version"
    
    # Step 5: Committare le modifiche al .env.local
    if git diff --quiet .env.local; then
        log "📝 Nessuna modifica al .env.local da committare"
    else
        log "📝 Committando modifiche al .env.local..."
        git add .env.local
        git commit -m "Update APP_VERSION to $new_version"
    fi
    
    # Step 6: Creare il tag
    log "🏷️  Creando tag v$new_version..."
    git tag -a "v$new_version" -m "Release $new_version ($release_type)"
    
    # Step 7: Push del tag
    log "📤 Push del tag..."
    git push origin "v$new_version"
    
    # Step 8: Push delle modifiche
    log "📤 Push delle modifiche..."
    git push origin main
    
    log "✅ Release $new_version creata con successo!"
    info "🏷️  Tag: v$new_version"
    info "📋 Tipo: $release_type"
    info "📝 APP_VERSION aggiornata in .env.local"
}

# Controllo se siamo nella directory corretta
if [ ! -f "docker-compose.yml" ]; then
    error "Script deve essere eseguito dalla directory root del progetto (dove si trova docker-compose.yml)"
fi

# Controllo se git è disponibile
if ! command -v git &> /dev/null; then
    error "git non è installato o non è nel PATH"
fi

# Controllo se siamo in un repository git
if [ ! -d ".git" ]; then
    error "Non sei in un repository git"
fi

# Parsing degli argomenti
if [ $# -eq 0 ]; then
    show_help
    exit 1
fi

RELEASE_TYPE=$1
HOTFIX_BASE=$2

# Validazione del tipo di release
case $RELEASE_TYPE in
    "major"|"minor")
        if [ $# -gt 1 ]; then
            warn "⚠️  Parametri extra ignorati per $RELEASE_TYPE"
        fi
        ;;
    "hotfix")
        if [ $# -lt 2 ]; then
            error "Per hotfix è obbligatorio specificare la release base"
        fi
        if [ $# -gt 2 ]; then
            warn "⚠️  Parametri extra ignorati per hotfix"
        fi
        ;;
    "help"|"-h"|"--help")
        show_help
        exit 0
        ;;
    *)
        error "Tipo di release non valido: $RELEASE_TYPE. Usa: major, minor, hotfix"
        ;;
esac

# Step 1: Recuperare l'ultima release
log "🔍 Recuperando l'ultima release..."
LAST_VERSION=$(get_last_version)
info "📋 Ultima versione trovata: $LAST_VERSION"

# Step 2: Costruire il nuovo numero della release
log "🔧 Costruendo nuova versione..."
NEW_VERSION=$(build_new_version "$LAST_VERSION" "$RELEASE_TYPE" "$HOTFIX_BASE")
info "📋 Nuova versione: $NEW_VERSION"

# Step 3: Chiedere conferma
echo ""
echo -e "${YELLOW}📋 Riepilogo Release:${NC}"
echo "   Tipo: $RELEASE_TYPE"
echo "   Da: $LAST_VERSION"
echo "   A: $NEW_VERSION"
if [ "$RELEASE_TYPE" = "hotfix" ]; then
    echo "   Base: $HOTFIX_BASE"
fi
echo ""

read -p "Confermi la creazione della release $NEW_VERSION? (y/N): " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${BLUE}Release annullata dall'utente.${NC}"
    exit 0
fi

# Step 4: Procedere con la nuova release
create_release "$NEW_VERSION" "$RELEASE_TYPE"

log "🎉 Release completata con successo!"
info "📊 Riepilogo:"
echo "   ✅ Tipo: $RELEASE_TYPE"
echo "   ✅ Versione precedente: $LAST_VERSION"
echo "   ✅ Nuova versione: $NEW_VERSION"
echo "   ✅ Tag creato: v$NEW_VERSION"
echo "   ✅ Push completato"
echo "   ✅ APP_VERSION aggiornata in .env.local"

info "🔗 Per visualizzare la release: git show v$NEW_VERSION" 