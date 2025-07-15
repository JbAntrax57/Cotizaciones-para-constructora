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
      <q-dialog v-model="dialog" persistent @hide="closeDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">{{ editingItem.id ? 'Editar' : 'Nuevo' }} Cliente</div>
          </q-card-section>
   
          <q-card-section>
            <q-form @submit="saveClient">
              <UppercaseInput 
                v-model="editingItem.business_name" 
                label="Razón Social" 
                :rules="[val => !!val || 'Campo requerido']" 
              />
              <UppercaseInput 
                v-model="editingItem.rfc" 
                label="RFC" 
                :rules="[val => !!val || 'Campo requerido']" 
              />
              <UppercaseInput 
                v-model="editingItem.address" 
                label="Dirección" 
                :rules="[val => !!val || 'Campo requerido']" 
              />
              <q-input 
                v-model="editingItem.phone" 
                label="Teléfono" 
                :rules="[val => !!val || 'Campo requerido']" 
              />
              <q-input 
                v-model="editingItem.email" 
                label="Email" 
                type="email" 
                :rules="[val => !!val || 'Campo requerido']" 
              />
              
              <div class="row justify-end q-mt-md">
                <q-btn label="Cancelar" color="negative" v-close-popup :disable="loading" />
                <q-btn label="Guardar" type="submit" color="primary" class="q-ml-sm" :loading="loading" />
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-dialog>
    </q-page>
   </template>
   
   <script>
   import { ref } from 'vue'
   import { api } from 'src/boot/axios'
   import DataTable from 'src/components/DataTable.vue'
   import { useNotifications } from 'src/composables/useNotifications'
   import { useCrudOperations } from 'src/composables/useCrudOperations'
   
   export default {
    name: 'ClientsPage',
    components: {
      DataTable
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
        loading: crudLoading,
        dialog,
        editingItem,
        openNewDialog,
        openEditDialog,
        closeDialog,
        saveItem,
        deleteItem
      } = useCrudOperations()
   
      const columns = [
        { name: 'business_name', label: 'Razón Social', field: 'business_name', sortable: true },
        { name: 'rfc', label: 'RFC', field: 'rfc', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'phone', label: 'Teléfono', field: 'phone', sortable: true },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]

      const openNewClientDialog = () => {
        openNewDialog(defaultClientData)
      }

      const editClient = (client) => {
        openEditDialog(client)
      }
   
      const loadClients = async () => {
        loading.value = true
        try {
          const response = await api.get('/clients')
          clients.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los clientes')
        } finally {
          loading.value = false
        }
      }
   
      const saveClient = async () => {
        const saveAction = async () => {
          if (editingItem.value.id) {
            return await api.put(`/clients/${editingItem.value.id}`, editingItem.value)
          } else {
            return await api.post('/clients', editingItem.value)
          }
        }

        const successMessage = editingItem.value.id ? 'Cliente actualizado correctamente' : 'Cliente agregado correctamente'
        
        await saveItem(saveAction, successMessage, loadClients)
      }
   
      const confirmDelete = async (client) => {
        const deleteAction = async () => {
          return await api.delete(`/clients/${client.id}`)
        }

        await deleteItem(deleteAction, client.business_name, loadClients)
      }
   
      loadClients()
   
      return {
        clients,
        columns,
        loading: crudLoading,
        dialog,
        editingItem,
        saveClient,
        editClient,
        confirmDelete,
        openNewClientDialog,
        closeDialog
      }
    }
   }
   </script>