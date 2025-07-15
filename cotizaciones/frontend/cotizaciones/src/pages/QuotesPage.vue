<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Cotizaciones</h5>
        <q-btn color="primary" icon="add" label="Nueva Cotización" :to="'/quotes/create'" />
      </div>
   
      <DataTable :rows="quotes" :columns="columns" :loading="loading">
        <template #actions="{ row }">
          <q-btn flat round color="info" icon="visibility" @click="viewQuote(row)" />
          <q-btn flat round color="primary" icon="edit" @click="editQuote(row)" />
          <q-btn flat round color="negative" icon="delete" @click="deleteQuote(row)" />
        </template>
        <template #status="{ value }">
          <q-chip :color="getStatusColor(value)" text-color="white">
            {{ getStatusLabel(value) }}
          </q-chip>
        </template>
      </DataTable>

      <!-- Loader para acciones -->
      <ActionLoader 
        :loading="actionLoading" 
        :message="actionMessage" 
      />
    </q-page>
   </template>
   
   <script>
   import { ref, onMounted } from 'vue'
   import { api } from 'boot/axios'
   import { useRouter } from 'vue-router'
   import DataTable from 'src/components/DataTable.vue'
   import ActionLoader from 'src/components/ActionLoader.vue'
   import { useNotifications } from 'src/composables/useNotifications'
   import { useDateFormatter } from 'src/composables/useDateFormatter'
   import { useNumberFormatter } from 'src/composables/useNumberFormatter'
   
   export default {
    name: 'QuotesPage',
    components: {
      DataTable,
      ActionLoader
    },
    setup() {
      const quotes = ref([])
      const loading = ref(false)
      const actionLoading = ref(false)
      const actionMessage = ref('')
      const router = useRouter()

      const { showSuccess, showError } = useNotifications()
      const { formatDate } = useDateFormatter()
      const { formatCurrency } = useNumberFormatter()
   
      const columns = [
        { name: 'id', label: 'Folio', field: 'id', sortable: true },
        { name: 'client', label: 'Cliente', field: row => row.client?.business_name },
        { name: 'project', label: 'Proyecto', field: row => row.project?.name },
        { name: 'total', label: 'Total', field: 'total', format: formatCurrency },
        { name: 'status', label: 'Estado', field: 'status' },
        { 
          name: 'created_at', 
          label: 'Fecha', 
          field: 'formatted_created_at',
          format: val => formatDate(val)
        },
        { name: 'actions', label: 'Acciones' }
      ]

      const loadQuotes = async () => {
        loading.value = true
        try {
          const response = await api.get('/quotes')
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          quotes.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar las cotizaciones')
        } finally {
          loading.value = false
        }
      }
   
      const getStatusColor = (status) => ({
        draft: 'grey',
        sent: 'blue',
        approved: 'positive',
        rejected: 'negative'
      }[status])
   
      const getStatusLabel = (status) => ({
        draft: 'Borrador',
        sent: 'Enviada',
        approved: 'Aprobada',
        rejected: 'Rechazada'
      }[status])
   
      const viewQuote = (quote) => {
        router.push(`/quotes/${quote.id}`)
      }

      const editQuote = (quote) => {
        router.push(`/quotes/${quote.id}/edit`)
      }

      const deleteQuote = async (quote) => {
        actionLoading.value = true
        actionMessage.value = 'Eliminando cotización...'
        
        try {
          await api.delete(`/quotes/${quote.id}`)
          showSuccess('Cotización eliminada correctamente')
          loadQuotes()
        } catch (error) {
          console.error(error)
          showError('Error al eliminar la cotización')
        } finally {
          actionLoading.value = false
        }
      }
   
      onMounted(loadQuotes)
   
      return {
        quotes,
        columns,
        loading,
        actionLoading,
        actionMessage,
        getStatusColor,
        getStatusLabel,
        viewQuote,
        editQuote,
        deleteQuote
      }
    }
   }
   </script>