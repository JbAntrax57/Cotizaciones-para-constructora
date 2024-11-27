<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Cotizaciones</h5>
        <q-btn color="primary" icon="add" label="Nueva Cotización" :to="'/quotes/create'" />
      </div>
   
      <q-table :rows="quotes" :columns="columns" row-key="id" :loading="loading">
        <template v-slot:body-cell-status="props">
          <q-td :props="props">
            <q-chip :color="getStatusColor(props.value)" text-color="white">
              {{ getStatusLabel(props.value) }}
            </q-chip>
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round color="info" icon="visibility" @click="viewQuote(props.row)" />
            <q-btn flat round color="primary" icon="edit" @click="editQuote(props.row)" />
            <q-btn flat round color="negative" icon="delete" @click="deleteQuote(props.row)" />
          </q-td>
        </template>
      </q-table>
    </q-page>
   </template>
   
   <script>
   import { ref, onMounted } from 'vue'
   import { api } from 'boot/axios'
   import { useRouter } from 'vue-router'
   
   export default {
    name: 'QuotesPage',
    setup() {
      const quotes = ref([])
      const loading = ref(false)
      const router = useRouter()
   
      const columns = [
        { name: 'id', label: 'Folio', field: 'id', sortable: true },
        { name: 'client', label: 'Cliente', field: row => row.client?.business_name },
        { name: 'project', label: 'Proyecto', field: row => row.project?.name },
        { name: 'total', label: 'Total', field: 'total', format: val => `$${val.toFixed(2)}` },
        { name: 'status', label: 'Estado', field: 'status' },
        { name: 'created_at', label: 'Fecha', field: 'created_at' },
        { name: 'actions', label: 'Acciones' }
      ]
   
      const loadQuotes = async () => {
        loading.value = true
        try {
          const response = await api.get('/quotes')
          quotes.value = response.data
        } catch (error) {
          console.error(error)
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
   
      onMounted(loadQuotes)
   
      return {
        quotes,
        columns,
        loading,
        getStatusColor,
        getStatusLabel,
        viewQuote
      }
    }
   }
   </script>