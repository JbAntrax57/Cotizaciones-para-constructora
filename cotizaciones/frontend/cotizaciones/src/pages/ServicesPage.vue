<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Servicios</h5>
        <q-btn color="primary" icon="add" label="Nuevo Servicio" @click="showDialog = true" />
      </div>
  
      <q-table
        :rows="services"
        :columns="columns"
        row-key="id"
        :loading="loading"
      >
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round color="primary" icon="edit" @click="editService(props.row)" />
            <q-btn flat round color="negative" icon="delete" @click="confirmDelete(props.row)" />
          </q-td>
        </template>
      </q-table>
  
      <q-dialog v-model="showDialog">
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
    </q-page>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { api } from 'boot/axios'
  
  export default {
    name: 'ServicesPage',
    setup() {
      const services = ref([])
      const loading = ref(false)
      const showDialog = ref(false)
      const editedService = ref({
        name: '',
        description: '',
        estimated_cost: 0
      })
  
      const columns = [
        { name: 'name', label: 'Nombre', field: 'name', sortable: true },
        { name: 'description', label: 'Descripción', field: 'description' },
        { name: 'estimated_cost', label: 'Costo Estimado', field: 'estimated_cost', format: val => `$${val.toFixed(2)}` },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]
  
      const loadServices = async () => {
        loading.value = true
        try {
          const response = await api.get('/services')
          services.value = response.data
        } catch (error) {
          console.error(error)
        } finally {
          loading.value = false
        }
      }
  
      const saveService = async () => {
        try {
          if (editedService.value.id) {
            await api.put(`/services/${editedService.value.id}`, editedService.value)
          } else {
            await api.post('/services', editedService.value)
          }
          showDialog.value = false
          loadServices()
        } catch (error) {
          console.error(error)
        }
      }
  
      loadServices()
  
      return {
        services,
        columns,
        loading,
        showDialog,
        editedService,
        saveService
      }
    }
  }
  </script>