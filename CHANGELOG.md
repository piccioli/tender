# Changelog

Tutte le modifiche notevoli a questo progetto saranno documentate in questo file.

Il formato è basato su [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
e questo progetto aderisce al [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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