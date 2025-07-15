<template>
  <q-page padding>
    <div class="row items-center justify-between q-mb-md">
      <h5 class="q-my-none">Configuración de Tipos de Construcción</h5>
    </div>

    <div class="row q-col-gutter-md">
      <!-- Lista de tipos de construcción -->
      <div class="col-12 col-md-4">
        <q-card>
          <q-card-section>
            <div class="text-h6">Tipos de Construcción</div>
          </q-card-section>
          <q-card-section>
            <q-list separator>
              <q-item 
                v-for="type in constructionTypes" 
                :key="type.id"
                clickable
                :active="selectedType?.id === type.id"
                @click="selectType(type)"
              >
                <q-item-section>
                  <q-item-label>{{ type.name }}</q-item-label>
                  <q-item-label caption>{{ type.description }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>
      </div>

      <!-- Configuración del tipo seleccionado -->
      <div class="col-12 col-md-8" v-if="selectedType">
        <q-card>
          <q-card-section>
            <div class="text-h6">{{ selectedType.name }} - Configuración</div>
          </q-card-section>

          <q-card-section>
            <q-tabs v-model="activeTab" class="text-grey" active-color="primary" indicator-color="primary" align="justify" narrow-indicator>
              <q-tab name="materials" label="Materiales" />
              <q-tab name="services" label="Servicios" />
            </q-tabs>

            <q-tab-panels v-model="activeTab" animated>
              <!-- Panel de Materiales -->
              <q-tab-panel name="materials">
                <div class="row items-center justify-between q-mb-md">
                  <h6>Materiales Requeridos</h6>
                  <q-btn color="primary" icon="add" label="Agregar Material" @click="openMaterialDialog" />
                </div>

                <q-table
                  :rows="typeMaterials"
                  :columns="materialColumns"
                  row-key="id"
                  :loading="loadingMaterials"
                >
                  <template #body-cell-actions="props">
                    <q-td :props="props">
                      <q-btn flat round color="primary" icon="edit" @click="editMaterial(props.row)" />
                      <q-btn flat round color="negative" icon="delete" @click="deleteMaterial(props.row)" />
                    </q-td>
                  </template>
                </q-table>
              </q-tab-panel>

              <!-- Panel de Servicios -->
              <q-tab-panel name="services">
                <div class="row items-center justify-between q-mb-md">
                  <h6>Servicios Requeridos</h6>
                  <q-btn color="primary" icon="add" label="Agregar Servicio" @click="openServiceDialog" />
                </div>

                <q-table
                  :rows="typeServices"
                  :columns="serviceColumns"
                  row-key="id"
                  :loading="loadingServices"
                >
                  <template #body-cell-actions="props">
                    <q-td :props="props">
                      <q-btn flat round color="primary" icon="edit" @click="editService(props.row)" />
                      <q-btn flat round color="negative" icon="delete" @click="deleteService(props.row)" />
                    </q-td>
                  </template>
                </q-table>
              </q-tab-panel>
            </q-tab-panels>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Diálogo para Material -->
    <q-dialog v-model="materialDialog" persistent>
      <q-card style="min-width: 500px">
        <q-card-section>
          <div class="text-h6">{{ editingMaterial.id ? 'Editar' : 'Agregar' }} Material</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveMaterial">
            <div class="row q-col-gutter-md">
              <div class="col-12">
                <GlobalSelect
                  v-model="editingMaterial.material_id"
                  :options="materialOptions"
                  label="Material"
                  icon="construction"
                  :rules="[val => !!val || 'Seleccione un material']"
                />
              </div>
              <div class="col-12">
                <q-input v-model="editingMaterial.application_type" label="Tipo de Aplicación" />
              </div>
              <div class="col-6">
                <q-input v-model.number="editingMaterial.quantity_per_unit" type="number" label="Cantidad por Unidad" step="0.001" />
              </div>
              <div class="col-6">
                <q-input v-model="editingMaterial.unit_measure" label="Unidad de Medida" />
              </div>
              <div class="col-12">
                <q-input v-model="editingMaterial.calculation_formula" label="Fórmula de Cálculo" />
                <div class="text-caption q-mt-sm">
                  Variables disponibles: floor_area, wall_area, perimeter, height
                </div>
              </div>
              <div class="col-12">
                <q-input v-model="editingMaterial.notes" type="textarea" label="Notas" />
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

    <!-- Diálogo para Servicio -->
    <q-dialog v-model="serviceDialog" persistent>
      <q-card style="min-width: 500px">
        <q-card-section>
          <div class="text-h6">{{ editingService.id ? 'Editar' : 'Agregar' }} Servicio</div>
        </q-card-section>

        <q-card-section>
          <q-form @submit="saveService">
            <div class="row q-col-gutter-md">
              <div class="col-12">
                <GlobalSelect
                  v-model="editingService.service_id"
                  :options="serviceOptions"
                  label="Servicio"
                  icon="build"
                  :rules="[val => !!val || 'Seleccione un servicio']"
                />
              </div>
              <div class="col-12">
                <q-input v-model="editingService.work_type" label="Tipo de Trabajo" />
              </div>
              <div class="col-6">
                <q-input v-model.number="editingService.rate_per_unit" type="number" label="Tarifa por Unidad" step="0.01" />
              </div>
              <div class="col-6">
                <q-input v-model="editingService.unit_measure" label="Unidad de Medida" />
              </div>
              <div class="col-12">
                <q-input v-model="editingService.calculation_formula" label="Fórmula de Cálculo" />
                <div class="text-caption q-mt-sm">
                  Variables disponibles: floor_area, wall_area, perimeter
                </div>
              </div>
              <div class="col-12">
                <q-input v-model="editingService.notes" type="textarea" label="Notas" />
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
import { ref, onMounted, computed } from 'vue'
import { api } from 'boot/axios'
import { useNotifications } from 'src/composables/useNotifications'

export default {
  name: 'ConstructionTypeConfig',
  setup() {
    const constructionTypes = ref([])
    const materials = ref([])
    const services = ref([])
    const typeMaterials = ref([])
    const typeServices = ref([])
    const selectedType = ref(null)
    const activeTab = ref('materials')
    const loadingMaterials = ref(false)
    const loadingServices = ref(false)

    const materialDialog = ref(false)
    const serviceDialog = ref(false)
    const editingMaterial = ref({})
    const editingService = ref({})

    const { showSuccess, showError } = useNotifications()

    const materialColumns = [
      { name: 'material_name', label: 'Material', field: 'material_name' },
      { name: 'application_type', label: 'Aplicación', field: 'application_type' },
      { name: 'quantity_per_unit', label: 'Cantidad/Unidad', field: 'quantity_per_unit' },
      { name: 'unit_measure', label: 'Unidad', field: 'unit_measure' },
      { name: 'actions', label: 'Acciones' }
    ]

    const serviceColumns = [
      { name: 'service_name', label: 'Servicio', field: 'service_name' },
      { name: 'work_type', label: 'Tipo de Trabajo', field: 'work_type' },
      { name: 'rate_per_unit', label: 'Tarifa/Unidad', field: 'rate_per_unit' },
      { name: 'unit_measure', label: 'Unidad', field: 'unit_measure' },
      { name: 'actions', label: 'Acciones' }
    ]

    const materialOptions = computed(() => {
      return materials.value.map(material => ({
        label: material.name,
        value: material.id
      }))
    })

    const serviceOptions = computed(() => {
      return services.value.map(service => ({
        label: service.name,
        value: service.id
      }))
    })

    const loadConstructionTypes = async () => {
      try {
        const response = await api.get('/construction-types')
        constructionTypes.value = response.data.data || response.data
      } catch (error) {
        console.error(error)
        showError('Error cargando tipos de construcción')
      }
    }

    const loadMaterials = async () => {
      try {
        const response = await api.get('/materials')
        materials.value = response.data.data || response.data
      } catch (error) {
        console.error(error)
        showError('Error cargando materiales')
      }
    }

    const loadServices = async () => {
      try {
        const response = await api.get('/services')
        services.value = response.data.data || response.data
      } catch (error) {
        console.error(error)
        showError('Error cargando servicios')
      }
    }

    const selectType = async (type) => {
      selectedType.value = type
      await loadTypeMaterials()
      await loadTypeServices()
    }

    const loadTypeMaterials = async () => {
      if (!selectedType.value) return
      
      loadingMaterials.value = true
      try {
        const response = await api.get(`/construction-types/${selectedType.value.id}/materials`)
        typeMaterials.value = response.data.data || response.data
      } catch (error) {
        console.error(error)
        showError('Error cargando materiales del tipo')
      } finally {
        loadingMaterials.value = false
      }
    }

    const loadTypeServices = async () => {
      if (!selectedType.value) return
      
      loadingServices.value = true
      try {
        const response = await api.get(`/construction-types/${selectedType.value.id}/services`)
        typeServices.value = response.data.data || response.data
      } catch (error) {
        console.error(error)
        showError('Error cargando servicios del tipo')
      } finally {
        loadingServices.value = false
      }
    }

    const openMaterialDialog = () => {
      editingMaterial.value = {
        construction_type_id: selectedType.value.id,
        material_id: null,
        application_type: '',
        quantity_per_unit: 0,
        unit_measure: '',
        calculation_formula: '',
        notes: ''
      }
      materialDialog.value = true
    }

    const editMaterial = (material) => {
      editingMaterial.value = { ...material }
      materialDialog.value = true
    }

    const saveMaterial = async () => {
      try {
        if (editingMaterial.value.id) {
          await api.put(`/construction-type-materials/${editingMaterial.value.id}`, editingMaterial.value)
          showSuccess('Material actualizado correctamente')
        } else {
          await api.post('/construction-type-materials', editingMaterial.value)
          showSuccess('Material agregado correctamente')
        }
        materialDialog.value = false
        await loadTypeMaterials()
      } catch (error) {
        console.error(error)
        showError('Error guardando material')
      }
    }

    const deleteMaterial = async (material) => {
      try {
        await api.delete(`/construction-type-materials/${material.id}`)
        showSuccess('Material eliminado correctamente')
        await loadTypeMaterials()
      } catch (error) {
        console.error(error)
        showError('Error eliminando material')
      }
    }

    const openServiceDialog = () => {
      editingService.value = {
        construction_type_id: selectedType.value.id,
        service_id: null,
        work_type: '',
        rate_per_unit: 0,
        unit_measure: '',
        calculation_formula: '',
        notes: ''
      }
      serviceDialog.value = true
    }

    const editService = (service) => {
      editingService.value = { ...service }
      serviceDialog.value = true
    }

    const saveService = async () => {
      try {
        if (editingService.value.id) {
          await api.put(`/construction-type-services/${editingService.value.id}`, editingService.value)
          showSuccess('Servicio actualizado correctamente')
        } else {
          await api.post('/construction-type-services', editingService.value)
          showSuccess('Servicio agregado correctamente')
        }
        serviceDialog.value = false
        await loadTypeServices()
      } catch (error) {
        console.error(error)
        showError('Error guardando servicio')
      }
    }

    const deleteService = async (service) => {
      try {
        await api.delete(`/construction-type-services/${service.id}`)
        showSuccess('Servicio eliminado correctamente')
        await loadTypeServices()
      } catch (error) {
        console.error(error)
        showError('Error eliminando servicio')
      }
    }

    onMounted(() => {
      loadConstructionTypes()
      loadMaterials()
      loadServices()
    })

    return {
      constructionTypes,
      materials,
      services,
      typeMaterials,
      typeServices,
      selectedType,
      activeTab,
      loadingMaterials,
      loadingServices,
      materialDialog,
      serviceDialog,
      editingMaterial,
      editingService,
      materialColumns,
      serviceColumns,
      materialOptions,
      serviceOptions,
      selectType,
      openMaterialDialog,
      editMaterial,
      saveMaterial,
      deleteMaterial,
      openServiceDialog,
      editService,
      saveService,
      deleteService
    }
  }
}
</script> 