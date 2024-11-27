<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Clientes</h5>
        <q-btn color="primary" icon="add" label="Nuevo Cliente" @click="showDialog = true" />
      </div>
   
      <q-table
        :rows="clients"
        :columns="columns"
        row-key="id"
        :loading="loading"
      >
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round color="primary" icon="edit" @click="editClient(props.row)" />
            <q-btn flat round color="negative" icon="delete" @click="confirmDelete(props.row)" />
          </q-td>
        </template>
      </q-table>
   
      <!-- Dialog para crear/editar -->
      <q-dialog v-model="showDialog" persistent>
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
    </q-page>
   </template>
   
   <script>
   import { ref } from 'vue'
   import { api } from 'src/boot/axios'
   
   export default {
    name: 'ClientsPage',
    setup () {
      const clients = ref([])
      const loading = ref(false)
      const showDialog = ref(false)
      const editedClient = ref({
        business_name: '',
        rfc: '',
        address: '',
        phone: '',
        email: ''
      })
   
      const columns = [
        { name: 'business_name', label: 'Razón Social', field: 'business_name', sortable: true },
        { name: 'rfc', label: 'RFC', field: 'rfc', sortable: true },
        { name: 'email', label: 'Email', field: 'email', sortable: true },
        { name: 'phone', label: 'Teléfono', field: 'phone', sortable: true },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]
   
      const loadClients = async () => {
        loading.value = true
        try {
          const response = await api.get('/clients')
          clients.value = response.data
        } catch (error) {
          console.error(error)
        } finally {
          loading.value = false
        }
      }
   
      const saveClient = async () => {
        try {
          if (editedClient.value.id) {
            await api.put(`/clients/${editedClient.value.id}`, editedClient.value)
          } else {
            await api.post('/clients', editedClient.value)
          }
          showDialog.value = false
          loadClients()
        } catch (error) {
          console.error(error)
        }
      }
   
      const editClient = (client) => {
        editedClient.value = { ...client }
        showDialog.value = true
      }
   
      const confirmDelete = async (client) => {
        try {
          await api.delete(`/clients/${client.id}`)
          loadClients()
        } catch (error) {
          console.error(error)
        }
      }
   
      loadClients()
   
      return {
        clients,
        columns,
        loading,
        showDialog,
        editedClient,
        saveClient,
        editClient,
        confirmDelete
      }
    }
   }
   </script>