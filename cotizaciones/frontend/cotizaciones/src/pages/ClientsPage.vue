<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Clientes</h5>
        <q-btn color="primary" icon="add" label="Nuevo Cliente" @click="openNewClientDialog" />
      </div>
   
      <DataTable
        :rows="clients"
        :columns="columns"
        :loading="loading"
      >
        <template #actions="{ row }">
          <q-btn flat round color="primary" icon="edit" @click="editClient(row)" />
          <q-btn flat round color="negative" icon="delete" @click="confirmDelete(row)" />
        </template>
      </DataTable>
   
      <!-- Dialog para crear/editar -->
      <q-dialog v-model="showDialog" persistent @hide="closeDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">{{ editedClient.id ? 'Editar' : 'Nuevo' }} Cliente</div>
          </q-card-section>
   
          <q-card-section>
            <q-form @submit="saveClient">
              <q-input v-model="editedClient.business_name" label="Razón Social" :rules="[val => !!val || 'Campo requerido']" />
              <q-input v-model="editedClient.rfc" label="RFC" :rules="[val => !!val || 'Campo requerido']" />
              <q-input v-model="editedClient.address" label="Dirección" :rules="[val => !!val || 'Campo requerido']" />
              <q-input v-model="editedClient.phone" label="Teléfono" :rules="[val => !!val || 'Campo requerido']" />
              <q-input v-model="editedClient.email" label="Email" type="email" :rules="[val => !!val || 'Campo requerido']" />
              
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
   import { api } from 'src/boot/axios'
   import DataTable from 'src/components/DataTable.vue'
   import ActionLoader from 'src/components/ActionLoader.vue'
   import { useNotifications } from 'src/composables/useNotifications'
   import { useModal } from 'src/composables/useModal'
   
   export default {
    name: 'ClientsPage',
    components: {
      DataTable,
      ActionLoader
    },
    setup () {
      const clients = ref([])
      const loading = ref(false)

      const defaultClientData = {
        business_name: '',
        rfc: '',
        address: '',
        phone: '',
        email: ''
      }

      const { showSuccess, showError } = useNotifications()
      const { 
        showDialog, 
        editedItem: editedClient, 
        actionLoading, 
        actionMessage,
        openNewDialog,
        openEditDialog,
        executeAction,
        closeDialog
      } = useModal(defaultClientData)
   
      const columns = [
        { name: 'business_name', label: 'Razón Social', field: 'business_name', sortable: true },
        { name: 'rfc', label: 'RFC', field: 'rfc', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'phone', label: 'Teléfono', field: 'phone', sortable: true },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]

      const openNewClientDialog = () => {
        openNewDialog()
      }
   
      const loadClients = async () => {
        loading.value = true
        try {
          const response = await api.get('/clients')
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          clients.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los clientes')
        } finally {
          loading.value = false
        }
      }
   
      const saveClient = async () => {
        const result = await executeAction(async () => {
          if (editedClient.value.id) {
            return await api.put(`/clients/${editedClient.value.id}`, editedClient.value)
          } else {
            return await api.post('/clients', editedClient.value)
          }
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadClients()
        } else {
          showError(result.message)
        }
      }
   
      const editClient = (client) => {
        openEditDialog(client)
      }
   
      const confirmDelete = async (client) => {
        const result = await executeAction(async () => {
          return await api.delete(`/clients/${client.id}`)
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadClients()
        } else {
          showError(result.message)
        }
      }
   
      loadClients()
   
      return {
        clients,
        columns,
        loading,
        actionLoading,
        actionMessage,
        showDialog,
        editedClient,
        saveClient,
        editClient,
        confirmDelete,
        openNewClientDialog
      }
    }
   }
   </script>