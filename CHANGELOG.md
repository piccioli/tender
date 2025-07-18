# Changelog

Tutte le modifiche notevoli a questo progetto saranno documentate in questo file.

Il formato è basato su [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
e questo progetto aderisce al [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.0] - 2024-06-29

### Added
- **Nuovo componente Nova MontFlow**: pagina personalizzata con descrizione dettagliata dei ruoli e permessi, stile coerente con la welcome page.
- **Script create_nova_page.sh** per la creazione automatica di nuove pagine Nova.
- **Seeder ruoli avanzato**: aggiunti 7 ruoli definiti per la gestione permessi.
- **Comando Artisan per cleanup ruoli e permessi**.
- **Migrazione per cleanup ruoli e permessi**.
- **Parametro ambiente per lo script di deploy**.

### Changed
- Sostituito il ruolo 'manager' con 'tender_manager' nelle policy dei tender.
- Spostata la logica di cleanup da una migration a un comando Artisan.
- Aggiornato provider e package per integrare il nuovo componente MontFlow.
- Rimozione dei ruoli e permessi dal menu admin di Nova.
- Aggiornata la configurazione di .env.local.

### Removed
- Rimosso il componente HelpMontFlow.
- Rimossa la directory .npm dal versionamento.

### Fixed
- Aggiunte le virgolette singole ad APP_VERSION in .env.local per evitare errori di parsing.

### Technical
- Script di pulizia repository (`git clean`).

## [Unreleased]

### Added
- Nuove funzionalità in sviluppo

### Changed
- Modifiche alle funzionalità esistenti

### Deprecated
- Funzionalità che verranno rimosse in futuro

### Removed
- Funzionalità rimosse

### Fixed
- Bug fixes

### Security
- Vulnerabilità di sicurezza risolte

## [1.2.0] - 2025-06-29

### Added
- **Sistema avanzato di gestione file .env** con separazione locale/produzione
- **Script di release automatizzato** con versioning semantico (major/minor/hotfix)
- **Gestione automatica APP_VERSION** con aggiornamento automatico durante le release
- **Sistema di backup automatico** del database prima di ogni deploy
- **Conferma utente per deploy** in produzione con avvertimenti sui rischi
- **Script setup_env.sh** per configurazione automatica ambiente locale
- **File .env.prod** per override configurazioni di produzione
- **Documentazione completa** per gestione ambienti e deployment
- **Sistema di link simbolici** per gestione file .env in sviluppo
- **Backup automatici** dei file .env in produzione con timestamp

### Changed
- **Script deploy.sh potenziato** con backup automatico e conferma utente
- **Gestione file .env migliorata** con separazione locale/produzione
- **Script release.sh completamente riscritto** con versioning semantico
- **Workflow di sviluppo ottimizzato** con setup automatico ambiente
- **Sicurezza rafforzata** per operazioni di deploy in produzione
- **Documentazione script aggiornata** con nuove funzionalità

### Fixed
- **Gestione file compressi** risolta per backup e restore
- **Controllo esistenza script** aggiunto per prevenire errori
- **Gestione errori migliorata** per operazioni critiche
- **Compatibilità cross-platform** per script (macOS/Linux)

### Technical
- **Versioning semantico automatizzato** con script release.sh
- **Gestione APP_VERSION dinamica** con aggiornamento automatico
- **Sistema di backup incrementale** per file di configurazione
- **Link simbolici** per gestione file .env in sviluppo
- **Override configurazioni** per ambiente di produzione
- **Conferma utente** con avvertimenti sui rischi per deploy
- **Backup automatico** del database prima di ogni deploy

## [1.1.0] - 2025-06-28

### Added
- **Script di backup database migliorato** con compressione automatica e link simbolico
- **Script di restore database** con download automatico dalla produzione
- **Sistema di conferma utente** per operazioni di deploy in produzione
- **Backup automatico del database** prima di ogni deploy
- **Gestione file compressi** (.tgz) per backup e restore
- **Sistema di help integrato** negli script di utilità
- **Configurazione personalizzabile** tramite variabili d'ambiente per restore
- **Pulizia automatica** dei file di backup obsoleti

### Changed
- **Script deploy.sh migliorato** con backup automatico e conferma utente
- **Script restore_db.sh ottimizzato** con download automatico e gestione errori migliorata
- **Script backup_db.sh potenziato** con compressione e link simbolico
- **Gestione errori migliorata** in tutti gli script di utilità
- **Sicurezza rafforzata** per operazioni di deploy in produzione

### Fixed
- **Gestione file compressi** risolta per backup e restore
- **Controllo esistenza script** aggiunto per prevenire errori
- **Selezione manuale file** eliminata in favore di selezione automatica
- **Gestione errori** migliorata per operazioni critiche

### Technical
- **Compressione automatica** dei backup database con tar
- **Link simbolico** per accesso rapido all'ultimo backup
- **Download automatico** da produzione per restore
- **Conferma utente** con avvertimenti sui rischi per deploy
- **Pulizia automatica** dei file di backup obsoleti

## [1.0.1] - 2025-06-29

### Added
- Documentazione completa del progetto
- README dettagliato con istruzioni di installazione e configurazione
- Release notes complete per v1.0.0
- Documentazione script di utilità e procedure di deployment
- Badge e informazioni sulla struttura del progetto
- Guida completa per il deployment in produzione

### Changed
- README.md completamente riscritto con informazioni specifiche del progetto
- Aggiornata documentazione per riflettere le funzionalità reali

## [1.0.0] - 2025-06-29

### Added
- **Dashboard Nova personalizzata** con logo Montagna Servizi SCPA
- **Sistema completo di gestione tender** con Laravel Nova
- **Filtri avanzati** per:
  - Creatore del tender
  - Editore del tender
  - Ruolo utente
  - Tipo di contratto
  - Tipo di procedura
  - Stato del tender
- **Sistema di autenticazione** integrato con autorizzazione basata su ruoli
- **Autenticazione a due fattori** (2FA)
- **Esportazione dati** con azioni personalizzate
- **Footer dinamico** con rilevamento automatico della versione
- **Configurazione APP_VERSION** per personalizzazione footer
- **Script di deployment** per produzione (`scripts/deploy.sh`)
- **Script di rollback** per tornare a versioni precedenti (`scripts/rollback.sh`)
- **Script di backup e restore** del database
- **Configurazione Docker** ottimizzata per produzione
- **Script di utilità** per gestione container e cache
- **Componenti Nova personalizzati**
- **CSS custom** per branding Montagna Servizi
- **Logo e assets** personalizzati
- **Configurazione Vite** per build ottimizzata
- **Migrazioni** per struttura completa del database
- **Seeder** per dati di test e configurazione iniziale
- **Politiche di accesso** per i tender
- **Sistema di permessi** basato su ruoli con Spatie Permission

### Changed
- **Ottimizzazioni per produzione** (cache config, route, view)
- **Configurazione Nova** personalizzata
- **File di configurazione** per CI/CD

### Technical
- **Configurazione APP_VERSION** per personalizzazione footer
- **Ottimizzazioni per produzione** (cache config, route, view)
- **Configurazione Nova** personalizzata
- **File di configurazione** per CI/CD
- **Database** con migrazioni e seeder
- **Frontend** con componenti Nova personalizzati

---

## Convenzioni per il Changelog

### Tipi di Modifica
- **Added** per nuove funzionalità
- **Changed** per modifiche alle funzionalità esistenti
- **Deprecated** per funzionalità che verranno rimosse
- **Removed** per funzionalità rimosse
- **Fixed** per bug fixes
- **Security** per vulnerabilità di sicurezza risolte

### Versioning
- **MAJOR.MINOR.PATCH** (es. 1.0.0)
- **MAJOR**: Cambiamenti incompatibili con le versioni precedenti
- **MINOR**: Nuove funzionalità compatibili con le versioni precedenti
- **PATCH**: Bug fixes compatibili con le versioni precedenti

### Commit Messages
- Usa prefissi convenzionali: `feat:`, `fix:`, `docs:`, `style:`, `refactor:`, `test:`, `chore:`
- Esempi:
  - `feat: add new tender export functionality`
  - `fix: resolve authentication issue with 2FA`
  - `docs: update installation instructions`
  - `style: improve Nova dashboard layout` 