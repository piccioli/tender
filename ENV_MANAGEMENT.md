# Gestione File .env

Questo documento spiega come gestire i file di configurazione dell'ambiente per il progetto.

## Struttura dei File

### File Principali

- **`.env.local`** (versionato): Configurazione locale di sviluppo
- **`.env`** (non versionato): Link simbolico a `.env.local` in locale, file fisico in produzione
- **`.env.prod`** (versionato): Override per le variabili di produzione
- **`.env.backup.*`** (non versionati): Backup automatici del file `.env` in produzione

### File di Esempio

- **`.env.example`**: Template di esempio per la configurazione

## Workflow di Sviluppo

### Ambiente Locale

1. **Setup iniziale:**
   ```bash
   ./scripts/setup_env.sh
   ```

2. **Modifica configurazione:**
   - Modifica direttamente il file `.env.local`
   - Il file `.env` punterà automaticamente a `.env.local`

3. **Verifica configurazione:**
   ```bash
   ls -la .env*
   # Dovrebbe mostrare: .env -> .env.local
   ```

### Ambiente di Produzione

Il deploy in produzione gestisce automaticamente i file `.env`:

1. **Backup** del file `.env` esistente
2. **Copia** `.env.local` → `.env`
3. **Applica override** da `.env.prod`

## Configurazione dei File

### .env.local

Contiene la configurazione completa per l'ambiente locale:

```env
APP_NAME=MontFlow
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
# ... altre variabili
```

### .env.prod

Contiene solo le variabili che devono essere sovrascritte in produzione:

```env
# Production environment overrides
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-production-domain.com

DB_HOST=your-production-db-host
DB_DATABASE=your-production-database
DB_USERNAME=your-production-db-user
DB_PASSWORD=your-production-db-password

# ... solo le variabili che cambiano in produzione
```

## Script Disponibili

### setup_env.sh

Configura l'ambiente locale creando il link simbolico:

```bash
./scripts/setup_env.sh
```

**Funzionalità:**
- Verifica esistenza di `.env.local`
- Crea link simbolico `.env` → `.env.local`
- Gestisce conflitti con file `.env` esistenti
- Crea backup automatici se necessario

### deploy.sh

Gestisce automaticamente i file `.env` in produzione:

```bash
./scripts/deploy.sh
```

**Processo:**
1. Backup del file `.env` esistente (`.env.backup.YYYYMMDD_HHMMSS`)
2. Eliminazione del file `.env` attuale
3. Copia di `.env.local` in `.env`
4. Applicazione degli override da `.env.prod`

## Best Practices

### Per Sviluppatori

1. **Non modificare mai** il file `.env` direttamente
2. **Modifica sempre** `.env.local` per cambiamenti locali
3. **Aggiungi nuove variabili** sia in `.env.local` che in `.env.prod` se necessario
4. **Testa le modifiche** localmente prima del deploy

### Per Amministratori di Sistema

1. **Configura correttamente** `.env.prod` con i valori di produzione
2. **Mantieni sicuri** i valori sensibili (password, chiavi API)
3. **Verifica i backup** dopo ogni deploy
4. **Monitora i log** per errori di configurazione

## Troubleshooting

### Problemi Comuni

**Link simbolico non funziona:**
```bash
./scripts/setup_env.sh
```

**File .env mancante:**
```bash
cp .env.local .env
```

**Override di produzione non applicati:**
- Verifica che `.env.prod` contenga le variabili corrette
- Controlla i log del deploy per errori

**Backup non trovati:**
```bash
ls -la .env.backup.*
```

### Verifica Configurazione

```bash
# Verifica link simbolico
ls -la .env

# Verifica contenuto
cat .env

# Verifica override di produzione
cat .env.prod
```

## Sicurezza

### File Versionati

- ✅ `.env.local` - Configurazione locale
- ✅ `.env.prod` - Override di produzione (senza valori sensibili)
- ✅ `.env.example` - Template di esempio

### File Non Versionati

- ❌ `.env` - Configurazione attuale
- ❌ `.env.backup.*` - Backup automatici
- ❌ File con password e chiavi sensibili

### Raccomandazioni

1. **Non committare mai** file con password o chiavi API
2. **Usa placeholder** in `.env.prod` per valori sensibili
3. **Configura valori sensibili** direttamente sul server di produzione
4. **Rivedi regolarmente** i file versionati per dati sensibili

## Esempi di Configurazione

### Database Locale
```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Database Produzione
```env
DB_HOST=production-db.example.com
DB_DATABASE=myapp_prod
DB_USERNAME=myapp_user
DB_PASSWORD=secure_password_here
```

### Mail Locale
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
```

### Mail Produzione
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.provider.com
MAIL_PORT=587
MAIL_USERNAME=noreply@myapp.com
MAIL_PASSWORD=mail_password_here
MAIL_ENCRYPTION=tls
``` 