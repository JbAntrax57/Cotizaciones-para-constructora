<template>
    <q-page padding>
      <h5>{{ isEdit ? 'Editar' : 'Nueva' }} Cotización</h5>
   
      <q-form @submit="saveQuote" class="q-gutter-md">
        <div class="row q-col-gutter-md">
          <!-- Información básica -->
          <div class="col-12 col-md-6">
            <q-card>
              <q-card-section>
                <div class="text-h6">Información General</div>
                <GlobalSelect
                  v-model="quote.client_id"
                  :options="clientOptions"
                  label="Cliente"
                  icon="person"
                  :rules="[val => !!val || 'Seleccione un cliente']"
                  @update:modelValue="onClientChange"
                />
                
                <GlobalSelect
                  v-model="quote.project_id"
                  :options="projectOptions"
                  label="Proyecto"
                  icon="work"
                  :rules="[val => !!val || 'Seleccione un proyecto']"
                  :disable="!quote.client_id"
                />
              </q-card-section>
            </q-card>
          </div>
   
          <!-- Servicios -->
          <div class="col-12">
            <q-card>
              <q-card-section>
                <div class="text-h6">Servicios</div>
                <q-btn 
                  label="Agregar Servicio" 
                  color="primary" 
                  flat 
                  @click="addService"
                  :loading="loadingServices"
                />
                
                <q-table 
                  :rows="quote.services" 
                  :columns="serviceColumns" 
                  row-key="id"
                  :loading="loadingServices"
                  :pagination="{ rowsPerPage: 0 }"
                >
                  <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                      <q-btn 
                        flat 
                        round 
                        color="negative" 
                        icon="delete" 
                        @click="removeService(props.rowIndex)"
                        :loading="loadingServices"
                      />
                    </q-td>
                  </template>
                </q-table>
              </q-card-section>
            </q-card>
          </div>
   
          <!-- Materiales -->
          <div class="col-12">
            <q-card>
              <q-card-section>
                <div class="text-h6">Materiales</div>
                <q-btn 
                  label="Agregar Material" 
                  color="primary" 
                  flat 
                  @click="addMaterial"
                  :loading="loadingMaterials"
                />
                
                <q-table 
                  :rows="quote.materials" 
                  :columns="materialColumns" 
                  row-key="id"
                  :loading="loadingMaterials"
                  :pagination="{ rowsPerPage: 0 }"
                >
                  <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                      <q-btn 
                        flat 
                        round 
                        color="negative" 
                        icon="delete" 
                        @click="removeMaterial(props.rowIndex)"
                        :loading="loadingMaterials"
                      />
                    </q-td>
                  </template>
                </q-table>
              </q-card-section>
            </q-card>
          </div>
   
          <!-- Totales -->
          <div class="col-12 col-md-4 q-ml-auto">
            <q-card>
              <q-card-section>
                <div class="text-h6">Resumen</div>
                <div class="row justify-between q-py-sm">
                  <div>Subtotal:</div>
                  <div>${{ safeToFixed(calculateSubtotal(), 2) }}</div>
                </div>
                <div class="row justify-between q-py-sm">
                  <div>IVA (16%):</div>
                  <div>${{ safeToFixed(calculateTax(), 2) }}</div>
                </div>
                <q-separator />
                <div class="row justify-between q-py-sm text-h6">
                  <div>Total:</div>
                  <div>${{ safeToFixed(calculateTotal(), 2) }}</div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
   
        <div class="row justify-end q-mt-md">
          <q-btn 
            label="Cancelar" 
            color="negative" 
            flat 
            :to="'/quotes'"
            :disable="saving"
          />
          <q-btn 
            label="Guardar" 
            type="submit" 
            color="primary" 
            class="q-ml-sm"
            :loading="saving"
          />
        </div>
      </q-form>
   
      <!-- Diálogo para agregar servicio -->
      <q-dialog v-model="serviceDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">Agregar Servicio</div>
          </q-card-section>
   
          <q-card-section>
            <GlobalSelect
              v-model="newService.service_id"
              :options="serviceOptions"
              label="Servicio"
              icon="build"
              :rules="[val => !!val || 'Seleccione un servicio']"
            />
            <q-input 
              v-model.number="newService.quantity" 
              type="number" 
              label="Cantidad"
              :rules="[val => val > 0 || 'Cantidad debe ser mayor a 0']"
            />
            <q-input 
              v-model.number="newService.unit_price" 
              type="number" 
              label="Precio Unitario"
              :rules="[val => val >= 0 || 'Precio debe ser mayor o igual a 0']"
            />
          </q-card-section>
   
          <q-card-actions align="right">
            <q-btn flat label="Cancelar" color="negative" v-close-popup />
            <q-btn 
              flat 
              label="Agregar" 
              color="primary" 
              @click="confirmAddService"
              :loading="addingService"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
   
      <!-- Diálogo para agregar material -->
      <q-dialog v-model="materialDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">Agregar Material</div>
          </q-card-section>
   
          <q-card-section>
            <GlobalSelect
              v-model="newMaterial.material_id"
              :options="materialOptions"
              label="Material"
              icon="construction"
              :rules="[val => !!val || 'Seleccione un material']"
            />
            <q-input 
              v-model.number="newMaterial.quantity" 
              type="number" 
              label="Cantidad"
              :rules="[val => val > 0 || 'Cantidad debe ser mayor a 0']"
            />
            <q-input 
              v-model.number="newMaterial.unit_price" 
              type="number" 
              label="Precio Unitario"
              :rules="[val => val >= 0 || 'Precio debe ser mayor o igual a 0']"
            />
          </q-card-section>
   
          <q-card-actions align="right">
            <q-btn flat label="Cancelar" color="negative" v-close-popup />
            <q-btn 
              flat 
              label="Agregar" 
              color="primary" 
              @click="confirmAddMaterial"
              :loading="addingMaterial"
            />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </q-page>
   </template>
   
   <script>
   import { ref, onMounted, computed } from 'vue'
   import { useRouter, useRoute } from 'vue-router'
   import { api } from 'boot/axios'
   import { useNotifications } from 'src/composables/useNotifications'
   import { useNumberFormatter } from 'src/composables/useNumberFormatter'
   
   export default {
    name: 'QuoteForm',
    setup() {
      const router = useRouter()
      const route = useRoute()
      const isEdit = computed(() => route.params.id !== undefined)
      const { showSuccess, showError, showInfo, showWarning } = useNotifications()
      const { safeToFixed } = useNumberFormatter()
   
      // Estados de loading
      const loadingClients = ref(false)
      const loadingProjects = ref(false)
      const loadingServices = ref(false)
      const loadingMaterials = ref(false)
      const saving = ref(false)
      const addingService = ref(false)
      const addingMaterial = ref(false)
   
      const clients = ref([])
      const allProjects = ref([]) // Todos los proyectos
      const filteredProjects = ref([]) // Proyectos filtrados por cliente
      const services = ref([])
      const materials = ref([])
   
      const quote = ref({
        client_id: null,
        project_id: null,
        services: [],
        materials: [],
        subtotal: 0,
        tax: 0,
        total: 0,
        status: 'draft'
      })
   
      const serviceDialog = ref(false)
      const materialDialog = ref(false)
      const newService = ref({})
      const newMaterial = ref({})
   
      const serviceColumns = [
        { name: 'name', label: 'Servicio', field: row => services.value.find(s => s.id === row.service_id)?.name },
        { name: 'quantity', label: 'Cantidad', field: 'quantity' },
        { name: 'unit_price', label: 'Precio Unitario', field: 'unit_price' },
        { name: 'total', label: 'Total', field: row => row.quantity * row.unit_price },
        { name: 'actions', label: 'Acciones' }
      ]
   
      const materialColumns = [
        { name: 'name', label: 'Material', field: row => materials.value.find(m => m.id === row.material_id)?.name },
        { name: 'quantity', label: 'Cantidad', field: 'quantity' },
        { name: 'unit_price', label: 'Precio Unitario', field: 'unit_price' },
        { name: 'total', label: 'Total', field: row => row.quantity * row.unit_price },
        { name: 'actions', label: 'Acciones' }
      ]
   
      // Opciones computadas para GlobalSelect
      const clientOptions = computed(() => {
        return clients.value.map(client => ({
          label: client.business_name,
          value: client.id
        }))
      })

      const projectOptions = computed(() => {
        return filteredProjects.value.map(project => ({
          label: project.name,
          value: project.id
        }))
      })

      const serviceOptions = computed(() => {
        return services.value.map(service => ({
          label: service.name,
          value: service.id
        }))
      })

      const materialOptions = computed(() => {
        return materials.value.map(material => ({
          label: material.name,
          value: material.id
        }))
      })

      const loadData = async () => {
        try {
          loadingClients.value = true
          loadingServices.value = true
          loadingMaterials.value = true
          
          const [clientsRes, projectsRes, servicesRes, materialsRes] = await Promise.all([
            api.get('/clients'),
            api.get('/projects'),
            api.get('/services'),
            api.get('/materials')
          ])
   
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          clients.value = clientsRes.data.data || clientsRes.data
          allProjects.value = projectsRes.data.data || projectsRes.data
          services.value = servicesRes.data.data || servicesRes.data
          materials.value = materialsRes.data.data || materialsRes.data
          
          showSuccess('Datos cargados correctamente')
        } catch (error) {
          console.error(error)
          showError('Error cargando datos')
        } finally {
          loadingClients.value = false
          loadingServices.value = false
          loadingMaterials.value = false
        }
      }
   
      const onClientChange = async (clientId) => {
        console.log('Cliente seleccionado:', clientId)
        
        // Limpiar proyecto seleccionado cuando cambia el cliente
        quote.value.project_id = null
        
        if (clientId) {
          try {
            loadingProjects.value = true
            console.log('Cargando proyectos para cliente:', clientId)
            // Cargar proyectos del cliente seleccionado
            const response = await api.get(`/projects/by-client/${clientId}`)
            console.log('Proyectos cargados:', response.data)
            // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
            filteredProjects.value = response.data.data || response.data
            
            showInfo(`${filteredProjects.value.length} proyecto(s) encontrado(s)`)
          } catch (error) {
            console.error('Error cargando proyectos del cliente:', error)
            filteredProjects.value = []
            showError('Error cargando proyectos del cliente')
          } finally {
            loadingProjects.value = false
          }
        } else {
          console.log('No hay cliente seleccionado, limpiando proyectos')
          filteredProjects.value = []
        }
      }
   
      const addService = () => {
        newService.value = {}
        serviceDialog.value = true
      }
   
      const confirmAddService = () => {
        if (!newService.value.service_id || !newService.value.quantity || !newService.value.unit_price) {
          showWarning('Complete todos los campos del servicio')
          return
        }
        
        addingService.value = true
        
        try {
          quote.value.services.push({
            service_id: newService.value.service_id,
            quantity: newService.value.quantity,
            unit_price: newService.value.unit_price
          })
          
          serviceDialog.value = false
          newService.value = {}
          
          showSuccess('Servicio agregado correctamente')
        } catch (error) {
          showError('Error agregando servicio')
        } finally {
          addingService.value = false
        }
      }
   
      const removeService = (index) => {
        quote.value.services.splice(index, 1)
        showInfo('Servicio eliminado')
      }
   
      const addMaterial = () => {
        newMaterial.value = {}
        materialDialog.value = true
      }
   
      const confirmAddMaterial = () => {
        if (!newMaterial.value.material_id || !newMaterial.value.quantity || !newMaterial.value.unit_price) {
          showWarning('Complete todos los campos del material')
          return
        }
        
        addingMaterial.value = true
        
        try {
          quote.value.materials.push({
            material_id: newMaterial.value.material_id,
            quantity: newMaterial.value.quantity,
            unit_price: newMaterial.value.unit_price
          })
          
          materialDialog.value = false
          newMaterial.value = {}
          
          showSuccess('Material agregado correctamente')
        } catch (error) {
          showError('Error agregando material')
        } finally {
          addingMaterial.value = false
        }
      }
   
      const removeMaterial = (index) => {
        quote.value.materials.splice(index, 1)
        showInfo('Material eliminado')
      }
   
      const calculateSubtotal = () => {
        const servicesTotal = quote.value.services.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0)
        const materialsTotal = quote.value.materials.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0)
        return servicesTotal + materialsTotal
      }
   
      const calculateTax = () => calculateSubtotal() * 0.16
      const calculateTotal = () => calculateSubtotal() + calculateTax()
   
      const saveQuote = async () => {
        if (!quote.value.client_id || !quote.value.project_id) {
          showWarning('Seleccione cliente y proyecto')
          return
        }
        
        saving.value = true
        
        try {
          const data = {
            ...quote.value,
            subtotal: calculateSubtotal(),
            tax: calculateTax(),
            total: calculateTotal()
          }
   
          if (isEdit.value) {
            await api.put(`/quotes/${route.params.id}`, data)
            showSuccess('Cotización actualizada correctamente')
          } else {
            await api.post('/quotes', data)
            showSuccess('Cotización creada correctamente')
          }
   
          router.push('/quotes')
        } catch (error) {
          console.error(error)
          showError('Error guardando cotización')
        } finally {
          saving.value = false
        }
      }
   
      onMounted(loadData)
   
      return {
        quote,
        clients,
        filteredProjects,
        services,
        materials,
        serviceDialog,
        materialDialog,
        newService,
        newMaterial,
        serviceColumns,
        materialColumns,
        isEdit,
        loadingClients,
        loadingProjects,
        loadingServices,
        loadingMaterials,
        saving,
        addingService,
        addingMaterial,
        onClientChange,
        addService,
        confirmAddService,
        removeService,
        addMaterial,
        confirmAddMaterial,
        removeMaterial,
        calculateSubtotal,
        calculateTax,
        calculateTotal,
        saveQuote
      }
    }
   }
   </script>