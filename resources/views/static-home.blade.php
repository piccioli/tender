<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale Montagna Servizi SCPA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h1 {
            font-size: 3rem;
            color: white;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            max-width: 600px;
            margin: 0 auto;
        }

        .main-content {
            background: white;
            border-radius: 15px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .feature h3 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .feature p {
            color: #666;
            line-height: 1.6;
        }

        .cta-section {
            text-align: center;
            padding: 3rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            margin-top: 2rem;
        }

        .cta-section h2 {
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            color: rgba(255,255,255,0.9);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-access {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 1rem 2rem;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-access:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .project-description {
            text-align: center;
            margin-bottom: 3rem;
        }

        .project-description h2 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .project-description p {
            font-size: 1.1rem;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .footer {
            text-align: center;
            color: rgba(255,255,255,0.8);
            padding: 2rem 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .main-content {
                padding: 2rem;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Gestinonale Montagna servizi</h1>
            <p>Piattaforma completa per la gestione di bandi di gara e procedure d'appalto</p>
        </div>

        <div class="main-content">
            <div class="project-description">
                <h2>Benvenuto nel Sistema di Gestione Tender</h2>
                <p>
                    Il Tender Management System è una piattaforma avanzata progettata per semplificare 
                    e ottimizzare la gestione completa dei bandi di gara e delle procedure d'appalto. 
                    Offre strumenti potenti per amministratori, gestori e utenti finali per gestire 
                    efficacemente tutto il ciclo di vita dei tender.
                </p>
            </div>

            <div class="features">
                <div class="feature">
                    <h3>📋 Gestione Tender Completa</h3>
                    <p>
                        Crea, modifica e gestisci bandi di gara con informazioni dettagliate su 
                        procedure, tipi di contratto, stati e scadenze. Sistema di categorizzazione 
                        avanzato per una gestione efficiente.
                    </p>
                </div>

                <div class="feature">
                    <h3>👥 Gestione Utenti e Permessi</h3>
                    <p>
                        Sistema di ruoli e permessi granulare che permette di definire chi può 
                        creare, modificare o visualizzare i tender. Gestione completa degli utenti 
                        con autenticazione sicura.
                    </p>
                </div>

                <div class="feature">
                    <h3>📊 Dashboard e Reportistica</h3>
                    <p>
                        Dashboard personalizzabili con metriche in tempo reale, filtri avanzati 
                        e sistema di esportazione dati. Monitoraggio completo dello stato dei tender.
                    </p>
                </div>

                <div class="feature">
                    <h3>🔍 Ricerca e Filtri Avanzati</h3>
                    <p>
                        Sistema di ricerca potente con filtri per creatore, editore, tipo di 
                        procedura, tipo di contratto e stato. Navigazione intuitiva e veloce.
                    </p>
                </div>

                <div class="feature">
                    <h3>📈 Tracciamento e Storico</h3>
                    <p>
                        Tracciamento completo delle modifiche con storico delle azioni e 
                        sistema di audit trail per garantire la trasparenza e la conformità.
                    </p>
                </div>

                <div class="feature">
                    <h3>🛡️ Sicurezza e Conformità</h3>
                    <p>
                        Implementazione di best practices di sicurezza con autenticazione 
                        a due fattori, crittografia dei dati e conformità alle normative vigenti.
                    </p>
                </div>
            </div>

            <div class="cta-section">
                <h2>Pronto per iniziare?</h2>
                <p>
                    Accedi alla piattaforma per gestire i tuoi tender in modo efficiente e professionale. 
                    Il sistema è progettato per semplificare il tuo lavoro quotidiano.
                </p>
                <a href="/nova/login" class="btn-access">🚀 Accedi al Sistema</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2025 Tender Management System. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html> 