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
                <q-select v-model="quote.client_id" :options="clients" 
                  option-label="business_name" option-value="id" 
                  label="Cliente" :rules="[val => !!val || 'Seleccione un cliente']" />
                
                <q-select v-model="quote.project_id" :options="projects"
                  option-label="name" option-value="id"
                  label="Proyecto" :rules="[val => !!val || 'Seleccione un proyecto']" />
              </q-card-section>
            </q-card>
          </div>
   
          <!-- Servicios -->
          <div class="col-12">
            <q-card>
              <q-card-section>
                <div class="text-h6">Servicios</div>
                <q-btn label="Agregar Servicio" color="primary" flat @click="addService" />
                
                <q-table :rows="quote.services" :columns="serviceColumns" row-key="id">
                  <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                      <q-btn flat round color="negative" icon="delete" @click="removeService(props.rowIndex)" />
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
                <q-btn label="Agregar Material" color="primary" flat @click="addMaterial" />
                
                <q-table :rows="quote.materials" :columns="materialColumns" row-key="id">
                  <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                      <q-btn flat round color="negative" icon="delete" @click="removeMaterial(props.rowIndex)" />
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
                  <div>${{ calculateSubtotal().toFixed(2) }}</div>
                </div>
                <div class="row justify-between q-py-sm">
                  <div>IVA (16%):</div>
                  <div>${{ calculateTax().toFixed(2) }}</div>
                </div>
                <q-separator />
                <div class="row justify-between q-py-sm text-h6">
                  <div>Total:</div>
                  <div>${{ calculateTotal().toFixed(2) }}</div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </div>
   
        <div class="row justify-end q-mt-md">
          <q-btn label="Cancelar" color="negative" flat :to="'/quotes'" />
          <q-btn label="Guardar" type="submit" color="primary" class="q-ml-sm" />
        </div>
      </q-form>
   
      <!-- Diálogo para agregar servicio -->
      <q-dialog v-model="serviceDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">Agregar Servicio</div>
          </q-card-section>
   
          <q-card-section>
            <q-select v-model="newService.service_id" :options="services"
              option-label="name" option-value="id" label="Servicio" />
            <q-input v-model.number="newService.quantity" type="number" label="Cantidad" />
            <q-input v-model.number="newService.unit_price" type="number" label="Precio Unitario" />
          </q-card-section>
   
          <q-card-actions align="right">
            <q-btn flat label="Cancelar" color="negative" v-close-popup />
            <q-btn flat label="Agregar" color="primary" @click="confirmAddService" />
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
            <q-select v-model="newMaterial.material_id" :options="materials"
              option-label="name" option-value="id" label="Material" />
            <q-input v-model.number="newMaterial.quantity" type="number" label="Cantidad" />
            <q-input v-model.number="newMaterial.unit_price" type="number" label="Precio Unitario" />
          </q-card-section>
   
          <q-card-actions align="right">
            <q-btn flat label="Cancelar" color="negative" v-close-popup />
            <q-btn flat label="Agregar" color="primary" @click="confirmAddMaterial" />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </q-page>
   </template>
   
   <script>
   import { ref, onMounted, computed } from 'vue'
   import { useRouter, useRoute } from 'vue-router'
   import { api } from 'boot/axios'
   
   export default {
    name: 'QuoteForm',
    setup() {
      const router = useRouter()
      const route = useRoute()
      const isEdit = computed(() => route.params.id !== undefined)
   
      const clients = ref([])
      const projects = ref([])
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
   
      const loadData = async () => {
        try {
          const [clientsRes, projectsRes, servicesRes, materialsRes] = await Promise.all([
            api.get('/clients'),
            api.get('/projects'),
            api.get('/services'),
            api.get('/materials')
          ])
   
          clients.value = clientsRes.data
          projects.value = projectsRes.data
          services.value = servicesRes.data
          materials.value = materialsRes.data
        } catch (error) {
          console.error(error)
        }
      }
   
      const calculateSubtotal = () => {
        const servicesTotal = quote.value.services.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0)
        const materialsTotal = quote.value.materials.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0)
        return servicesTotal + materialsTotal
      }
   
      const calculateTax = () => calculateSubtotal() * 0.16
      const calculateTotal = () => calculateSubtotal() + calculateTax()
   
      const saveQuote = async () => {
        try {
          const data = {
            ...quote.value,
            subtotal: calculateSubtotal(),
            tax: calculateTax(),
            total: calculateTotal()
          }
   
          if (isEdit.value) {
            await api.put(`/quotes/${route.params.id}`, data)
          } else {
            await api.post('/quotes', data)
          }
   
          router.push('/quotes')
        } catch (error) {
          console.error(error)
        }
      }
   
      onMounted(loadData)
   
      return {
        quote,
        clients,
        projects,
        services,
        materials,
        serviceDialog,
        materialDialog,
        newService,
        newMaterial,
        serviceColumns,
        materialColumns,
        isEdit,
        calculateSubtotal,
        calculateTax,
        calculateTotal,
        saveQuote
      }
    }
   }
   </script>