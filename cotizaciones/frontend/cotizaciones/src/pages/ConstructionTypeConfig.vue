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
                  :pagination="{ rowsPerPage: 10 }"
                  no-data-label="No hay materiales configurados"
                  no-results-label="No se encontraron materiales"
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
                  :pagination="{ rowsPerPage: 10 }"
                  no-data-label="No hay servicios configurados"
                  no-results-label="No se encontraron servicios"
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

      <!-- Mensaje cuando no hay tipo seleccionado -->
      <div class="col-12 col-md-8" v-else>
        <q-card>
          <q-card-section class="text-center">
            <q-icon name="info" size="48px" color="grey-5" />
            <div class="text-h6 q-mt-md text-grey-6">Selecciona un tipo de construcción</div>
            <div class="text-caption text-grey-5">Elige un tipo de construcción de la lista para configurar sus materiales y servicios</div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Diálogo para Material -->
    <q-dialog v-model="materialDialog" persistent @hide="closeMaterialDialog">
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
                <UppercaseInput 
                  v-model="editingMaterial.application_type" 
                  label="Tipo de Aplicación" 
                  :rules="[val => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-6">
                <q-input v-model.number="editingMaterial.quantity_per_unit" type="number" label="Cantidad por Unidad" step="0.001" :rules="[val => val > 0 || 'Debe ser mayor a 0']" />
              </div>
              <div class="col-6">
                <UppercaseInput 
                  v-model="editingMaterial.unit_measure" 
                  label="Unidad de Medida" 
                  :rules="[val => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-12">
                <q-input v-model="editingMaterial.calculation_formula" label="Fórmula de Cálculo" :rules="[val => !!val || 'Campo requerido']" />
                <div class="text-caption q-mt-sm">
                  Variables disponibles: floor_area, wall_area, perimeter, height
                </div>
              </div>
              <div class="col-12">
                <q-input v-model="editingMaterial.notes" type="textarea" label="Notas" />
              </div>
            </div>

            <div class="row justify-end q-mt-md">
              <q-btn label="Cancelar" color="negative" v-close-popup :disable="materialLoading" />
              <q-btn label="Guardar" type="submit" color="primary" class="q-ml-sm" :loading="materialLoading" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <!-- Diálogo para Servicio -->
    <q-dialog v-model="serviceDialog" persistent @hide="closeServiceDialog">
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
                <UppercaseInput 
                  v-model="editingService.work_type" 
                  label="Tipo de Trabajo" 
                  :rules="[val => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-6">
                <q-input v-model.number="editingService.rate_per_unit" type="number" label="Tarifa por Unidad" step="0.01" :rules="[val => val > 0 || 'Debe ser mayor a 0']" />
              </div>
              <div class="col-6">
                <UppercaseInput 
                  v-model="editingService.unit_measure" 
                  label="Unidad de Medida" 
                  :rules="[val => !!val || 'Campo requerido']"
                />
              </div>
              <div class="col-12">
                <q-input v-model="editingService.calculation_formula" label="Fórmula de Cálculo" :rules="[val => !!val || 'Campo requerido']" />
                <div class="text-caption q-mt-sm">
                  Variables disponibles: floor_area, wall_area, perimeter
                </div>
              </div>
              <div class="col-12">
                <q-input v-model="editingService.notes" type="textarea" label="Notas" />
              </div>
            </div>

            <div class="row justify-end q-mt-md">
              <q-btn label="Cancelar" color="negative" v-close-popup :disable="serviceLoading" />
              <q-btn label="Guardar" type="submit" color="primary" class="q-ml-sm" :loading="serviceLoading" />
            </div>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useQuasar } from 'quasar'
import { api } from 'boot/axios'
import { useNotifications } from 'src/composables/useNotifications'
import { useCrudOperations } from 'src/composables/useCrudOperations'

export default {
  name: 'ConstructionTypeConfig',
  setup() {
    const $q = useQuasar()
    const constructionTypes = ref([])
    const materials = ref([])
    const services = ref([])
    const typeMaterials = ref([])
    const typeServices = ref([])
    const selectedType = ref(null)
    const activeTab = ref('materials')
    const loadingMaterials = ref(false)
    const loadingServices = ref(false)

    const { showSuccess, showError } = useNotifications()
    
    // Instancia para materiales
    const materialCrud = useCrudOperations()
    const { 
      loading: materialLoading,
      dialog: materialDialog,
      editingItem: editingMaterial,
      openNewDialog: openNewMaterialDialog,
      openEditDialog: editMaterial,
      closeDialog: closeMaterialDialog,
      saveItem: saveMaterialItem,
      deleteItem: deleteMaterialItem
    } = materialCrud

    // Instancia para servicios
    const serviceCrud = useCrudOperations()
    const { 
      loading: serviceLoading,
      dialog: serviceDialog,
      editingItem: editingService,
      openNewDialog: openNewServiceDialog,
      openEditDialog: editService,
      closeDialog: closeServiceDialog,
      saveItem: saveServiceItem,
      deleteItem: deleteServiceItem
    } = serviceCrud

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
      const defaultData = {
        construction_type_id: selectedType.value.id,
        material_id: null,
        application_type: '',
        quantity_per_unit: 0,
        unit_measure: '',
        calculation_formula: '',
        notes: ''
      }
      openNewMaterialDialog(defaultData)
    }

    const openServiceDialog = () => {
      const defaultData = {
        construction_type_id: selectedType.value.id,
        service_id: null,
        work_type: '',
        rate_per_unit: 0,
        unit_measure: '',
        calculation_formula: '',
        notes: ''
      }
      openNewServiceDialog(defaultData)
    }

    const saveMaterial = async () => {
      const saveAction = async () => {
        if (editingMaterial.value.id) {
          return await api.put(`/construction-type-materials/${editingMaterial.value.id}`, editingMaterial.value)
        } else {
          return await api.post('/construction-type-materials', editingMaterial.value)
        }
      }

      const successMessage = editingMaterial.value.id ? 'Material actualizado correctamente' : 'Material agregado correctamente'
      
      await saveMaterialItem(saveAction, successMessage, loadTypeMaterials)
    }

    const deleteMaterial = async (material) => {
      const deleteAction = async () => {
        return await api.delete(`/construction-type-materials/${material.id}`)
      }

      await deleteMaterialItem(deleteAction, material.material_name, loadTypeMaterials)
    }

    const saveService = async () => {
      const saveAction = async () => {
        if (editingService.value.id) {
          return await api.put(`/construction-type-services/${editingService.value.id}`, editingService.value)
        } else {
          return await api.post('/construction-type-services', editingService.value)
        }
      }

      const successMessage = editingService.value.id ? 'Servicio actualizado correctamente' : 'Servicio agregado correctamente'
      
      await saveServiceItem(saveAction, successMessage, loadTypeServices)
    }

    const deleteService = async (service) => {
      const deleteAction = async () => {
        return await api.delete(`/construction-type-services/${service.id}`)
      }

      await deleteServiceItem(deleteAction, service.service_name, loadTypeServices)
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
      closeMaterialDialog,
      saveMaterial,
      deleteMaterial,
      openServiceDialog,
      editService,
      closeServiceDialog,
      saveService,
      deleteService
    }
  }
}
</script> 