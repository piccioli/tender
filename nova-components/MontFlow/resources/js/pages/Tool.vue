<template>
  <div>
    <Head title="MontFlow - Gestione Flussi di Lavoro della Cooperativa Montagna Servizi SCPA" />

    <Heading class="mb-6">MontFlow - Gestione Flussi di Lavoro della Cooperativa Montagna Servizi SCPA</Heading>


    <!-- Roles Description Card -->
    <Card class="p-6 mb-6">
      <h2 class="dark:text-white text-xl font-semibold mb-4">Descrizione dei Ruoli in MontFlow</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Admin Role -->
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border-l-4 border-red-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-red-800 dark:text-red-200">Admin</h3>
          </div>
          <p class="dark:text-red-200 text-sm">Amministratore generale di sistema, accede a tutte le funzionalità</p>
        </div>

        <!-- Tender Manager Role -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border-l-4 border-blue-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-blue-800 dark:text-blue-200">Tender Manager</h3>
          </div>
          <p class="dark:text-blue-200 text-sm">Coordinatore del gruppo che gestisce i bandi</p>
        </div>

        <!-- Tender Editor Role -->
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-l-4 border-green-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-green-800 dark:text-green-200">Tender Editor</h3>
          </div>
          <p class="dark:text-green-200 text-sm">Redattori di bandi</p>
        </div>

        <!-- Tender PM Role -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 border-l-4 border-purple-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-purple-800 dark:text-purple-200">Tender PM</h3>
          </div>
          <p class="dark:text-purple-200 text-sm">Project manager di un bando / progetto</p>
        </div>

        <!-- Team Member Role -->
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border-l-4 border-yellow-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-yellow-800 dark:text-yellow-200">Team Member</h3>
          </div>
          <p class="dark:text-yellow-200 text-sm">Membri del team di lavoro di Montagna Servizi</p>
        </div>

        <!-- Team Manager Role -->
        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border-l-4 border-indigo-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-indigo-800 dark:text-indigo-200">Team Manager</h3>
          </div>
          <p class="dark:text-indigo-200 text-sm">Coordinatore del team di lavoro (assegnazione compiti / scrum master)</p>
        </div>

        <!-- Customer Operator Role -->
        <div class="bg-pink-50 dark:bg-pink-900/20 rounded-lg p-4 border-l-4 border-pink-500">
          <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-pink-600 dark:text-pink-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
            </svg>
            <h3 class="dark:text-white font-semibold text-pink-800 dark:text-pink-200">Customer Operator</h3>
          </div>
          <p class="dark:text-pink-200 text-sm">Relazioni con i clienti (funzionalità CRM)</p>
        </div>
      </div>
    </Card>

    
  </div>
</template>

<script>
export default {
  name: 'Tool',
  
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
/* Custom styles for the MontFlow page */
.card {
  transition: all 0.3s ease;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>