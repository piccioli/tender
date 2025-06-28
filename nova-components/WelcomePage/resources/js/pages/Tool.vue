<template>
  <div>
    <Head title="Gestionale Montagna Servizi" />

    <Heading class="mb-6">Benvenuto in Gestionale Montagna Servizi</Heading>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Main Welcome Card -->
      <Card class="flex flex-col items-center justify-center p-8" style="min-height: 400px">
        <div class="text-center">
          <div class="mb-6">
            <img
              src="/img/logo-montagna-servizi.png"
              alt="Logo Montagna Servizi"
              class="mx-auto mb-4"
              style="max-width: 220px; max-height: 120px;"
            />

            <h1 class="dark:text-white text-3xl font-bold mb-4">
              Gestionale Montagna Servizi
            </h1>

            <p class="dark:text-white text-lg opacity-80 mb-6 leading-relaxed">
              Sistema di gestione completo per servizi di montagna, 
              gestione gare d'appalto e amministrazione delle risorse.
            </p>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6">
              <p class="text-blue-800 dark:text-blue-200 text-sm">
                <strong>Versione:</strong> 1.0.0 | 
                <strong>Ambiente:</strong> {{ environment }}
              </p>
            </div>
          </div>
        </div>
      </Card>

      <!-- Features Card -->
      <Card class="p-6">
        <h2 class="dark:text-white text-xl font-semibold mb-4">Funzionalità Principali</h2>
        
        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-6 h-6 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="dark:text-white font-medium">Gestione Gare d'Appalto</h3>
              <p class="dark:text-gray-300 text-sm opacity-80">Gestione completa delle procedure di gara e documentazione</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="dark:text-white font-medium">Controllo Utenti e Permessi</h3>
              <p class="dark:text-gray-300 text-sm opacity-80">Sistema di autorizzazioni avanzato con ruoli personalizzabili</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-6 h-6 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="dark:text-white font-medium">Dashboard Analitiche</h3>
              <p class="dark:text-gray-300 text-sm opacity-80">Monitoraggio in tempo reale delle attività e performance</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-6 h-6 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div>
              <h3 class="dark:text-white font-medium">Esportazione Dati</h3>
              <p class="dark:text-gray-300 text-sm opacity-80">Generazione di report e export in vari formati</p>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- Quick Actions Card -->
    <Card class="p-6">
      <h2 class="dark:text-white text-xl font-semibold mb-4">Azioni Rapide</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button 
          @click="navigateToTenders"
          class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors"
        >
          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <div class="text-left">
            <div class="dark:text-white font-medium">Gestione Gare</div>
            <div class="dark:text-gray-300 text-sm">Visualizza e gestisci le gare d'appalto</div>
          </div>
        </button>

        <button 
          @click="navigateToUsers"
          class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors"
        >
          <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
          </svg>
          <div class="text-left">
            <div class="dark:text-white font-medium">Utenti</div>
            <div class="dark:text-gray-300 text-sm">Gestisci utenti e permessi</div>
          </div>
        </button>

        <button 
          @click="navigateToDashboard"
          class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors"
        >
          <svg class="w-6 h-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
          <div class="text-left">
            <div class="dark:text-white font-medium">Dashboard</div>
            <div class="dark:text-gray-300 text-sm">Visualizza statistiche e report</div>
          </div>
        </button>
      </div>
    </Card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      environment: process.env.NODE_ENV === 'production' ? 'Produzione' : 'Sviluppo'
    }
  },
  
  mounted() {
    // Component mounted
  },

  methods: {
    navigateToTenders() {
      Nova.visit('/resources/tenders')
    },

    navigateToUsers() {
      Nova.visit('/resources/users')
    },

    navigateToDashboard() {
      Nova.visit('/')
    }
  }
}
</script>

<style scoped>
/* Custom styles for the welcome page */
.card {
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
