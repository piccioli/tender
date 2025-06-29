# Script di Supporto Docker

Questa directory contiene script utili per gestire l'ambiente Docker del progetto Laravel.

## Script disponibili:

### 🚀 start.sh
Avvia tutti i servizi Docker (app, database, apache)
```bash
./scripts/start.sh
```

### 🛑 stop.sh
Ferma tutti i servizi Docker
```bash
./scripts/stop.sh
```

### 🔄 restart.sh
Riavvia tutti i servizi Docker
```bash
./scripts/restart.sh
```

### 📋 logs.sh
Visualizza i log di tutti i servizi in tempo reale
```bash
./scripts/logs.sh
```

### ⚙️ artisan.sh
Esegue comandi Artisan Laravel
```bash
./scripts/artisan.sh migrate
./scripts/artisan.sh make:controller UserController
./scripts/artisan.sh list
```

### 📦 composer.sh
Esegue comandi Composer
```bash
./scripts/composer.sh install
./scripts/composer.sh require package/name
./scripts/composer.sh update
```

### 🐚 shell.sh
Accede alla shell del container app
```bash
./scripts/shell.sh
```

### 📊 status.sh
Visualizza lo stato di container, network e volumi
```bash
./scripts/status.sh
```

### 🗄️ db.sh
Accede al database
```bash
./scripts/db.sh
```

### 📚 help.sh
Mostra questa guida rapida con tutti gli script disponibili
```bash
./scripts/help.sh
```

## 🏷️ Script di Release:

### release.sh
Script per la gestione delle release con versioning semantico:
- Crea tag git per le nuove versioni
- Supporta major, minor e hotfix
- Gestisce automaticamente l'incremento delle versioni
- Richiede conferma prima di procedere

```bash
# Release major (es: 1.0.0 -> 2.0.0)
./scripts/release.sh major

# Release minor (es: 1.0.0 -> 1.1.0)
./scripts/release.sh minor

# Release hotfix (es: 1.0.0 -> 1.0.1)
./scripts/release.sh hotfix 1.0.0

# Mostra help
./scripts/release.sh help
```

**Tipi di release:**
- **major**: Incrementa la versione major (cambiamenti incompatibili)
- **minor**: Incrementa la versione minor (nuove funzionalità compatibili)
- **hotfix**: Incrementa la versione patch (correzioni di bug)

**Note:**
- Per hotfix è obbligatorio specificare la release base
- Lo script recupera automaticamente l'ultima versione da git
- Richiede conferma prima di procedere con la release
- Verifica che non ci siano modifiche non committate

## 🚀 Script di Deploy per Produzione:

### deploy.sh
Script completo per il deploy in produzione che automatizza:
- Ferma i container Docker
- Esegue git pull per aggiornare il codice
- Riavvia i container Docker
- Esegue composer update
- Lancia le migrazioni del database
- Pulisce tutte le cache
- Ottimizza per la produzione
- Testa la connessione all'applicazione

```bash
./scripts/deploy.sh
```

### rollback.sh
Script per il rollback in caso di problemi:
- Torna a un commit specifico
- Riavvia l'ambiente
- Reinstalla le dipendenze
- Ottimizza per la produzione

```bash
./scripts/rollback.sh <commit-hash>
# Esempio:
./scripts/rollback.sh abc1234
```

## Informazioni utili:

- **Web server**: http://localhost:8000
- **Database PostgreSQL**: localhost:5432
- **Credenziali DB**: laravel/password
- **Database**: laravel

## Note:

- Tutti gli script devono essere eseguiti dalla directory principale del progetto
- Assicurati che Docker sia in esecuzione prima di usare gli script
- Gli script verificano automaticamente la presenza di docker-compose.yml
- Gli script di deploy sono progettati per la produzione e includono controlli di sicurezza
