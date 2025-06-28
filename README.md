# 🏔️ Gestionale Montagna Servizi SCPA

[![Release](https://img.shields.io/badge/release-v1.0.0-blue.svg)](https://github.com/piccioli/tender/releases/tag/v1.0.0)
[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Nova](https://img.shields.io/badge/Nova-4.x-purple.svg)](https://nova.laravel.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)

Sistema di gestione tender completo per **Montagna Servizi SCPA**, sviluppato con Laravel Nova e ottimizzato per ambienti Docker.

## 🚀 Caratteristiche Principali

- **📊 Dashboard Nova personalizzata** con branding Montagna Servizi
- **📋 Gestione completa tender** con filtri avanzati e ricerca
- **🔐 Sistema di autenticazione** con autorizzazione basata su ruoli
- **📤 Esportazione dati** con azioni personalizzate
- **🔄 Script di deployment** e rollback per produzione
- **📱 Interfaccia responsive** ottimizzata per tutti i dispositivi

## 🛠️ Tecnologie Utilizzate

- **Backend:** Laravel 10.x
- **Admin Panel:** Laravel Nova 4.x
- **Database:** MySQL/PostgreSQL
- **Container:** Docker & Docker Compose
- **Frontend:** Vue.js, Tailwind CSS
- **Autenticazione:** Spatie Permission

## 📦 Installazione Rapida

### Prerequisiti
- Docker e Docker Compose
- Git
- PHP 8.0+ (per sviluppo locale)

### Setup
```bash
# 1. Clona il repository
git clone https://github.com/piccioli/tender.git
cd tender

# 2. Avvia i container Docker
docker-compose up -d

# 3. Installa le dipendenze
docker-compose exec app composer install

# 4. Configura l'ambiente
cp .env.example .env
docker-compose exec app php artisan key:generate

# 5. Esegui le migrazioni e i seeder
docker-compose exec app php artisan migrate --seed

# 6. Ottimizza per produzione
docker-compose exec app php artisan optimize
```

### Accesso
- **URL:** http://localhost:8000
- **Nova Admin:** http://localhost:8000/nova
- **Credenziali default:** Controlla il seeder per le credenziali

## 🔧 Configurazione

### Variabili d'Ambiente Importanti
```env
APP_NAME="Gestionale Montagna Servizi SCPA"
APP_VERSION="v1.0.0"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=tender
DB_USERNAME=tender_user
DB_PASSWORD=secure_password
```

### Personalizzazione Versione
Per aggiornare la versione visualizzata nel footer:
```env
APP_VERSION="v1.1.0"
```

## 📋 Script di Utilità

Il progetto include script per semplificare le operazioni:

```bash
# Deployment in produzione
./scripts/deploy.sh

# Rollback a versione precedente
./scripts/rollback.sh <commit-hash>

# Backup database
./scripts/backup_db.sh

# Restore database
./scripts/restore_db.sh <backup-file>

# Gestione container
./scripts/start.sh    # Avvia container
./scripts/stop.sh     # Ferma container
./scripts/restart.sh  # Riavvia container
./scripts/status.sh   # Stato container

# Pulizia e ottimizzazione
./scripts/clear.sh    # Pulisce cache
./scripts/logs.sh     # Visualizza log
```

## 🏗️ Struttura del Progetto

```
tender/
├── app/
│   ├── Nova/           # Risorse Nova personalizzate
│   ├── Models/         # Modelli Eloquent
│   ├── Policies/       # Politiche di autorizzazione
│   └── Http/           # Controller e middleware
├── database/
│   ├── migrations/     # Migrazioni database
│   └── seeders/        # Seeder per dati iniziali
├── nova-components/    # Componenti Nova custom
├── scripts/           # Script di utilità
├── docker/            # Configurazioni Docker
└── resources/         # Assets frontend
```

## 🔐 Sicurezza

- **Autenticazione a due fattori** (2FA)
- **Autorizzazione basata su ruoli** con Spatie Permission
- **Politiche di accesso** per ogni risorsa
- **Validazione input** robusta
- **Protezione CSRF** attiva

## 📊 Funzionalità Tender

### Filtri Disponibili
- **Creatore:** Filtra per utente che ha creato il tender
- **Editore:** Filtra per utente che ha modificato il tender
- **Ruolo:** Filtra per ruolo dell'utente
- **Tipo Contratto:** Filtra per tipo di contratto
- **Tipo Procedura:** Filtra per tipo di procedura
- **Stato:** Filtra per stato del tender

### Azioni
- **Esportazione dati** in formato CSV/Excel
- **Bulk actions** per operazioni multiple
- **Azioni personalizzate** per workflow specifici

## 🚀 Deployment

### Produzione con Docker
```bash
# 1. Clona il repository sul server
git clone https://github.com/piccioli/tender.git
cd tender

# 2. Configura l'ambiente di produzione
cp .env.example .env
# Modifica .env con le configurazioni di produzione

# 3. Deploy automatico
./scripts/deploy.sh
```

### Rollback
```bash
# In caso di problemi, torna alla versione precedente
./scripts/rollback.sh <commit-hash>
```

## 📈 Monitoraggio

### Log
```bash
# Visualizza log dell'applicazione
./scripts/logs.sh

# Log specifici
docker-compose logs -f app
docker-compose logs -f db
```

### Backup
```bash
# Backup automatico del database
./scripts/backup_db.sh

# Backup manuale
docker-compose exec db mysqldump -u root -p tender > backup.sql
```

## 🤝 Contribuire

1. Fork del progetto
2. Crea un branch per la feature (`git checkout -b feature/AmazingFeature`)
3. Commit delle modifiche (`git commit -m 'Add some AmazingFeature'`)
4. Push al branch (`git push origin feature/AmazingFeature`)
5. Apri una Pull Request

## 📝 Changelog

Vedi [RELEASE_NOTES.md](RELEASE_NOTES.md) per il changelog completo.

## 📄 Licenza

Questo progetto è sviluppato per **Montagna Servizi SCPA** e non è open source.

## 📞 Supporto

- **Sviluppatore:** Alessio Piccioli
- **Email:** piccioli@netseven.it
- **Azienda:** Montagna Servizi SCPA

---

**Versione:** v1.0.0  
**Ultimo aggiornamento:** 29 Giugno 2025
