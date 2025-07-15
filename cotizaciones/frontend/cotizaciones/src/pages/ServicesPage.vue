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
  
      <q-dialog v-model="dialog" persistent @hide="closeDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">{{ editingItem.id ? 'Editar' : 'Nuevo' }} Servicio</div>
          </q-card-section>
  
          <q-card-section>
            <q-form @submit="saveService">
              <UppercaseInput 
                v-model="editingItem.name" 
                label="Nombre" 
                :rules="[val => !!val || 'Campo requerido']" 
              />
              <q-input 
                v-model="editingItem.description" 
                type="textarea" 
                label="Descripción" 
              />
              <q-input 
                v-model.number="editingItem.estimated_cost" 
                type="number" 
                label="Costo Estimado" 
                prefix="$" 
                :rules="[val => val >= 0 || 'Debe ser mayor o igual a 0']"
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
  import { api } from 'boot/axios'
  import DataTable from 'src/components/DataTable.vue'
  import { useNotifications } from 'src/composables/useNotifications'
  import { useCrudOperations } from 'src/composables/useCrudOperations'
  import { useNumberFormatter } from 'src/composables/useNumberFormatter'
  
  export default {
    name: 'ServicesPage',
    components: {
      DataTable
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
        loading: crudLoading,
        dialog,
        editingItem,
        openNewDialog,
        openEditDialog,
        closeDialog,
        saveItem,
        deleteItem
      } = useCrudOperations()

      const openNewServiceDialog = () => {
        openNewDialog(defaultServiceData)
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
          services.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los servicios')
        } finally {
          loading.value = false
        }
      }
  
      const saveService = async () => {
        const saveAction = async () => {
          if (editingItem.value.id) {
            return await api.put(`/services/${editingItem.value.id}`, editingItem.value)
          } else {
            return await api.post('/services', editingItem.value)
          }
        }

        const successMessage = editingItem.value.id ? 'Servicio actualizado correctamente' : 'Servicio agregado correctamente'
        
        await saveItem(saveAction, successMessage, loadServices)
      }

      const confirmDelete = async (service) => {
        const deleteAction = async () => {
          return await api.delete(`/services/${service.id}`)
        }

        await deleteItem(deleteAction, service.name, loadServices)
      }
  
      loadServices()
  
      return {
        services,
        columns,
        loading: crudLoading,
        dialog,
        editingItem,
        saveService,
        editService,
        confirmDelete,
        openNewServiceDialog,
        closeDialog
      }
    }
  }
  </script>