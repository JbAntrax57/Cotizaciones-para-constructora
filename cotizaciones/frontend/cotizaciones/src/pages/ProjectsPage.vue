<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Proyectos</h5>
        <q-btn color="primary" icon="add" label="Nuevo Proyecto" @click="showDialog = true" />
      </div>
   
      <q-table :rows="projects" :columns="columns" row-key="id" :loading="loading">
        <template v-slot:body-cell-status="props">
          <q-td :props="props">
            <q-chip :color="getStatusColor(props.value)" text-color="white">
              {{ getStatusLabel(props.value) }}
            </q-chip>
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round color="primary" icon="edit" @click="editProject(props.row)" />
            <q-btn flat round color="negative" icon="delete" @click="confirmDelete(props.row)" />
          </q-td>
        </template>
      </q-table>
   
      <q-dialog v-model="showDialog">
        <q-card style="min-width: 500px">
          <q-card-section>
            <div class="text-h6">{{ editedProject.id ? 'Editar' : 'Nuevo' }} Proyecto</div>
          </q-card-section>
   
          <q-card-section>
            <q-form @submit="saveProject">
              <div class="row q-col-gutter-md">
                <div class="col-12">
                  <q-select v-model="editedProject.client_id" :options="clients" 
                    option-label="business_name" option-value="id" label="Cliente" 
                    :rules="[val => !!val || 'Seleccione un cliente']" />
                </div>
                <div class="col-12">
                  <q-input v-model="editedProject.name" label="Nombre del Proyecto" 
                    :rules="[val => !!val || 'Campo requerido']" />
                </div>
                <div class="col-12">
                  <q-input v-model="editedProject.description" type="textarea" label="Descripción" />
                </div>
                <div class="col-12">
                  <q-input v-model="editedProject.location" label="Ubicación" />
                </div>
                <div class="col-6">
                  <q-input v-model="editedProject.start_date" type="date" label="Fecha Inicio" />
                </div>
                <div class="col-6">
                  <q-input v-model="editedProject.end_date" type="date" label="Fecha Fin" />
                </div>
                <div class="col-12">
                  <q-select v-model="editedProject.status" :options="statusOptions" 
                    label="Estado" emit-value map-options />
                </div>
              </div>
              
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
   import { ref, onMounted } from 'vue'
   import { api } from 'boot/axios'
   
   export default {
    name: 'ProjectsPage',
    setup() {
      const projects = ref([])
      const clients = ref([])
      const loading = ref(false)
      const showDialog = ref(false)
      
      const statusOptions = [
        { label: 'En Planeación', value: 'planning' },
        { label: 'En Progreso', value: 'in_progress' },
        { label: 'Completado', value: 'completed' }
      ]
   
      const editedProject = ref({
        client_id: null,
        name: '',
        description: '',
        location: '',
        start_date: '',
        end_date: '',
        status: 'planning'
      })
   
      const columns = [
        { name: 'name', label: 'Nombre', field: 'name', sortable: true },
        { 
          name: 'client', 
          label: 'Cliente', 
          field: row => row.client?.business_name,
          sortable: true 
        },
        { name: 'location', label: 'Ubicación', field: 'location' },
        { name: 'start_date', label: 'Inicio', field: 'start_date' },
        { name: 'end_date', label: 'Fin', field: 'end_date' },
        { name: 'status', label: 'Estado', field: 'status' },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]
   
      const loadProjects = async () => {
        loading.value = true
        try {
          const response = await api.get('/projects')
          projects.value = response.data
        } catch (error) {
          console.error(error)
        } finally {
          loading.value = false
        }
      }
   
      const loadClients = async () => {
        try {
          const response = await api.get('/clients')
          clients.value = response.data
        } catch (error) {
          console.error(error)
        }
      }
   
      const getStatusColor = (status) => {
        const colors = {
          planning: 'blue',
          in_progress: 'orange',
          completed: 'green'
        }
        return colors[status] || 'grey'
      }
   
      const getStatusLabel = (status) => {
        const labels = {
          planning: 'En Planeación',
          in_progress: 'En Progreso',
          completed: 'Completado'
        }
        return labels[status] || status
      }
    const saveProject = async () => {
    try {
        if (editedProject.value.id) {
        await api.put(`/projects/${editedProject.value.id}`, editedProject.value)
        } else {
        await api.post('/projects', editedProject.value)
        }
        showDialog.value = false
        loadProjects()
    } catch (error) {
        console.error(error)
    }
    }

    const editProject = (project) => {
    editedProject.value = { ...project }
    showDialog.value = true
    }

    const confirmDelete = async (project) => {
    try {
        await api.delete(`/projects/${project.id}`)
        loadProjects()
    } catch (error) {
        console.error(error)
    }
    }
   
      onMounted(() => {
        loadProjects()
        loadClients()
      })
   
      return {
        projects,
        clients,
        columns,
        loading,
        showDialog,
        editedProject,
        statusOptions,
        getStatusColor,
        getStatusLabel,
        saveProject,
        editProject,
        confirmDelete
      }
    }
   }
   </script>