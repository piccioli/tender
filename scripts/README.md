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

## Informazioni utili:

- **Web server**: http://localhost:8000
- **Database PostgreSQL**: localhost:5432
- **Credenziali DB**: laravel/password
- **Database**: laravel

## Note:

- Tutti gli script devono essere eseguiti dalla directory principale del progetto
- Assicurati che Docker sia in esecuzione prima di usare gli script
- Gli script verificano automaticamente la presenza di docker-compose.yml
