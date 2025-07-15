<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Servicios</h5>
        <q-btn color="primary" icon="add" label="Nuevo Servicio" @click="openNewServiceDialog" />
      </div>
  
      <DataTable
        :rows="services"
        :columns="columns"
        :loading="loading"
      >
        <template #actions="{ row }">
          <q-btn flat round color="primary" icon="edit" @click="editService(row)" />
          <q-btn flat round color="negative" icon="delete" @click="confirmDelete(row)" />
        </template>
      </DataTable>
  
      <q-dialog v-model="showDialog" persistent @hide="closeDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">{{ editedService.id ? 'Editar' : 'Nuevo' }} Servicio</div>
          </q-card-section>
  
          <q-card-section>
            <q-form @submit="saveService">
              <q-input v-model="editedService.name" label="Nombre" :rules="[val => !!val || 'Campo requerido']" />
              <q-input v-model="editedService.description" type="textarea" label="Descripción" />
              <q-input v-model.number="editedService.estimated_cost" type="number" label="Costo Estimado" prefix="$" />
              
              <div class="row justify-end q-mt-md">
                <q-btn label="Cancelar" color="negative" v-close-popup />
                <q-btn label="Guardar" type="submit" color="primary" class="q-ml-sm" />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>

      <!-- Loader para acciones -->
      <ActionLoader 
        :loading="actionLoading" 
        :message="actionMessage" 
      />
    </q-page>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { api } from 'boot/axios'
  import DataTable from 'src/components/DataTable.vue'
  import ActionLoader from 'src/components/ActionLoader.vue'
  import { useNotifications } from 'src/composables/useNotifications'
  import { useModal } from 'src/composables/useModal'
  import { useNumberFormatter } from 'src/composables/useNumberFormatter'
  
  export default {
    name: 'ServicesPage',
    components: {
      DataTable,
      ActionLoader
    },
    setup() {
      const services = ref([])
      const loading = ref(false)

      const defaultServiceData = {
        name: '',
        description: '',
        estimated_cost: 0
      }

      const { showSuccess, showError } = useNotifications()
      const { formatCurrency } = useNumberFormatter()
      const { 
        showDialog, 
        editedItem: editedService, 
        actionLoading, 
        actionMessage,
        openNewDialog,
        openEditDialog,
        executeAction,
        closeDialog
      } = useModal(defaultServiceData)

      const openNewServiceDialog = () => {
        openNewDialog()
      }

      const editService = (service) => {
        openEditDialog(service)
      }
  
      const columns = [
        { name: 'name', label: 'Nombre', field: 'name', sortable: true },
        { name: 'description', label: 'Descripción', field: 'description' },
        { name: 'estimated_cost', label: 'Costo Estimado', field: 'estimated_cost', format: formatCurrency },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]
  
      const loadServices = async () => {
        loading.value = true
        try {
          const response = await api.get('/services')
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          services.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los servicios')
        } finally {
          loading.value = false
        }
      }
  
      const saveService = async () => {
        const result = await executeAction(async () => {
          if (editedService.value.id) {
            return await api.put(`/services/${editedService.value.id}`, editedService.value)
          } else {
            return await api.post('/services', editedService.value)
          }
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadServices()
        } else {
          showError(result.message)
        }
      }

      const confirmDelete = async (service) => {
        const result = await executeAction(async () => {
          return await api.delete(`/services/${service.id}`)
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadServices()
        } else {
          showError(result.message)
        }
      }
  
      loadServices()
  
      return {
        services,
        columns,
        loading,
        actionLoading,
        actionMessage,
        showDialog,
        editedService,
        saveService,
        editService,
        confirmDelete,
        openNewServiceDialog,
        closeDialog
      }
    }
  }
  </script>