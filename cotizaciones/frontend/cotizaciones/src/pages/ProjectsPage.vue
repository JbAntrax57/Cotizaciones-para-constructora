<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Proyectos</h5>
        <q-btn color="primary" icon="add" label="Nuevo Proyecto" @click="openNewProjectDialog" />
      </div>
   
      <DataTable :rows="projects" :columns="columns" :loading="loading">
        <template #actions="{ row }">
          <q-btn flat round color="primary" icon="edit" @click="editProject(row)" />
          <q-btn flat round color="negative" icon="delete" @click="confirmDelete(row)" />
        </template>
        <template #status="{ value }">
          <q-chip :color="getStatusColor(value)" text-color="white">
            {{ getStatusLabel(value) }}
          </q-chip>
        </template>
      </DataTable>
   
      <q-dialog v-model="showDialog" persistent @hide="closeDialog">
        <q-card style="min-width: 500px">
          <q-card-section>
            <div class="text-h6">{{ editedProject.id ? 'Editar' : 'Nuevo' }} Proyecto</div>
          </q-card-section>
   
          <q-card-section>
            <q-form @submit="saveProject">
              <div class="row q-col-gutter-md">
                <div class="col-12">
                  <GlobalSelect
                    v-model="editedProject.client_id"
                    :options="clientOptions"
                    label="Cliente"
                    icon="person"
                    :rules="[val => !!val || 'Seleccione un cliente']"
                  />
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
                  <GlobalSelect
                    v-model="editedProject.status"
                    :options="statusOptions"
                    label="Estado"
                    icon="flag"
                  />
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

      <!-- Loader para acciones -->
      <ActionLoader 
        :loading="actionLoading" 
        :message="actionMessage" 
      />
    </q-page>
   </template>
   
   <script>
   import { ref, onMounted, computed } from 'vue'
   import { api } from 'boot/axios'
   import DataTable from 'src/components/DataTable.vue'
   import ActionLoader from 'src/components/ActionLoader.vue'
   import { useNotifications } from 'src/composables/useNotifications'
   import { useDateFormatter } from 'src/composables/useDateFormatter'
   import { useModal } from 'src/composables/useModal'
   
   export default {
    name: 'ProjectsPage',
    components: {
      DataTable,
      ActionLoader
    },
    setup() {
      const projects = ref([])
      const clients = ref([])
      const loading = ref(false)
      
      const statusOptions = [
        { label: 'En Planeación', value: 'planning' },
        { label: 'En Progreso', value: 'in_progress' },
        { label: 'Completado', value: 'completed' }
      ]

      const defaultProjectData = {
        client_id: null,
        name: '',
        description: '',
        location: '',
        start_date: '',
        end_date: '',
        status: 'planning'
      }

      const { showSuccess, showError } = useNotifications()
      const { formatDate, formatDateForInput } = useDateFormatter()
      const { 
        showDialog, 
        editedItem: editedProject, 
        actionLoading, 
        actionMessage,
        openNewDialog,
        openEditDialog,
        executeAction,
        closeDialog
      } = useModal(defaultProjectData)

      const openNewProjectDialog = async () => {
        // Asegurar que los clientes estén cargados
        if (clients.value.length === 0) {
          await loadClients()
        }
        openNewDialog()
      }
   
      const columns = [
        { name: 'name', label: 'Nombre', field: 'name', sortable: true },
        { 
          name: 'client', 
          label: 'Cliente', 
          field: row => row.client?.business_name,
          sortable: true 
        },
        { name: 'location', label: 'Ubicación', field: 'location' },
        { 
          name: 'start_date', 
          label: 'Inicio', 
          field: 'formatted_start_date',
          format: val => formatDate(val)
        },
        { 
          name: 'end_date', 
          label: 'Fin', 
          field: 'formatted_end_date',
          format: val => formatDate(val)
        },
        { name: 'status', label: 'Estado', field: 'status' },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]

      const loadProjects = async () => {
        loading.value = true
        try {
          const response = await api.get('/projects')
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          projects.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los proyectos')
        } finally {
          loading.value = false
        }
      }
   
      const loadClients = async () => {
        try {
          const response = await api.get('/clients')
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          clients.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los clientes')
        }
      }

      // Transformar clientes al formato requerido por GlobalSelect
      const clientOptions = computed(() => {
        return clients.value.map(client => ({
          label: client.business_name,
          value: client.id
        }))
      })
   
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
        const result = await executeAction(async () => {
          if (editedProject.value.id) {
            return await api.put(`/projects/${editedProject.value.id}`, editedProject.value)
          } else {
            return await api.post('/projects', editedProject.value)
          }
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadProjects()
        } else {
          showError(result.message)
        }
      }

      const editProject = async (project) => {
        // Asegurar que los clientes estén cargados
        if (clients.value.length === 0) {
          await loadClients()
        }
        
        // Formatear las fechas para los inputs de tipo date
        const projectData = { ...project }
        if (projectData.start_date) {
          projectData.start_date = formatDateForInput(projectData.start_date)
        }
        if (projectData.end_date) {
          projectData.end_date = formatDateForInput(projectData.end_date)
        }
        
        openEditDialog(projectData)
      }

      const confirmDelete = async (project) => {
        const result = await executeAction(async () => {
          return await api.delete(`/projects/${project.id}`)
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadProjects()
        } else {
          showError(result.message)
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
        actionLoading,
        actionMessage,
        showDialog,
        editedProject,
        statusOptions,
        clientOptions,
        getStatusColor,
        getStatusLabel,
        saveProject,
        editProject,
        confirmDelete,
        clearForm: () => {}, // No longer needed
        openNewProjectDialog
      }
    }
   }
   </script>