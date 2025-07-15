<template>
  <q-page padding>
    <div class="row q-col-gutter-md">
      <!-- Formulario de entrada -->
      <div class="col-12 col-md-6">
        <q-card>
          <q-card-section>
            <div class="text-h6">Calculadora Automática</div>
            <p class="text-caption">Ingresa las dimensiones y el sistema calculará automáticamente los materiales y costos</p>
          </q-card-section>

          <q-card-section>
            <q-form @submit="calculateConstruction" class="q-gutter-md">
              <!-- Tipo de construcción -->
              <GlobalSelect
                v-model="form.construction_type"
                :options="constructionTypeOptions"
                label="Tipo de Construcción"
                icon="home"
                :rules="[val => !!val || 'Selecciona un tipo de construcción']"
              />

              <!-- Dimensiones -->
              <div class="row q-col-gutter-sm">
                <div class="col-4">
                  <q-input
                    v-model.number="form.length"
                    type="number"
                    label="Largo (m)"
                    :rules="[val => val > 0 || 'Largo debe ser mayor a 0']"
                    step="0.1"
                    :loading="calculating"
                  />
                </div>
                <div class="col-4">
                  <q-input
                    v-model.number="form.width"
                    type="number"
                    label="Ancho (m)"
                    :rules="[val => val > 0 || 'Ancho debe ser mayor a 0']"
                    step="0.1"
                    :loading="calculating"
                  />
                </div>
                <div class="col-4">
                  <q-input
                    v-model.number="form.height"
                    type="number"
                    label="Alto (m)"
                    :rules="[val => val > 0 || 'Alto debe ser mayor a 0']"
                    step="0.1"
                    :loading="calculating"
                  />
                </div>
              </div>

              <q-btn
                label="Calcular"
                type="submit"
                color="primary"
                :loading="calculating"
                class="full-width"
              />
            </q-form>
          </q-card-section>
        </q-card>
      </div>

      <!-- Resultados y Tabs -->
      <div class="col-12 col-md-6" v-if="results">
        <q-card>
          <q-card-section>
            <div class="text-h6">Resultados del Cálculo</div>
          </q-card-section>

          <!-- Tabs de Materiales y Servicios -->
          <q-card-section>
            <q-tabs v-model="activeTab" class="text-grey" active-color="primary" indicator-color="primary" align="justify" narrow-indicator>
              <q-tab name="materials" label="Materiales" />
              <q-tab name="services" label="Servicios" />
            </q-tabs>
            <q-tab-panels v-model="activeTab" animated>
              <q-tab-panel name="materials">
                <div class="text-subtitle2 q-mb-sm">Materiales Necesarios:</div>
                <q-list separator>
                  <q-item v-for="material in editableResults.materials" :key="material.material_id">
                    <q-item-section>
                      <q-item-label>{{ material.name }}</q-item-label>
                      <q-item-label caption>{{ material.application }}</q-item-label>
                    </q-item-section>
                    <q-item-section>
                      <q-input v-model.number="material.quantity" type="number" min="0" label="Cantidad" dense @update:model-value="recalculateTotals" />
                    </q-item-section>
                    <q-item-section>
                      <q-input v-model.number="material.unit_cost" type="number" min="0" label="Precio Unitario" prefix="$" dense @update:model-value="recalculateTotals" />
                    </q-item-section>
                    <q-item-section side>
                      <q-item-label>Total:</q-item-label>
                      <q-item-label caption="${{ formatCurrency(material.total_cost) }}"></q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-tab-panel>
              <q-tab-panel name="services">
                <div class="text-subtitle2 q-mb-sm">Servicios:</div>
                <q-list separator>
                  <q-item v-for="service in editableResults.services" :key="service.service_id">
                    <q-item-section>
                      <q-item-label>{{ service.name }}</q-item-label>
                      <q-item-label caption>{{ service.description }}</q-item-label>
                    </q-item-section>
                    <q-item-section>
                      <q-input v-model.number="service.quantity" type="number" min="0" label="Cantidad" dense @update:model-value="recalculateTotals" />
                    </q-item-section>
                    <q-item-section>
                      <q-input v-model.number="service.unit_cost" type="number" min="0" label="Precio Unitario" prefix="$" dense @update:model-value="recalculateTotals" />
                    </q-item-section>
                    <q-item-section side>
                      <q-item-label>Total:</q-item-label>
                      <q-item-label caption="${{ formatCurrency(service.total_cost) }}"></q-item-label>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-tab-panel>
            </q-tab-panels>
            <div class="row justify-end q-mt-md">
              <q-btn label="Restaurar valores originales" color="secondary" flat @click="restoreOriginals" />
            </div>
          </q-card-section>

          <!-- Botón para generar cotización -->
          <q-card-section>
            <q-btn
              label="Generar cotización"
              color="positive"
              class="full-width q-mt-md"
              @click="showQuoteModal = true"
            />
          </q-card-section>

          <!-- Modal para seleccionar tipo de cotización -->
          <q-dialog v-model="showQuoteModal">
            <q-card style="min-width: 350px">
              <q-card-section>
                <div class="text-h6">¿Qué deseas presentar en la cotización?</div>
              </q-card-section>
              <q-card-section>
                <q-select
                  v-model="selectedQuoteType"
                  :options="quoteTypeOptions"
                  label="Tipo de cotización"
                  emit-value
                  map-options
                />
              </q-card-section>
              <q-card-actions align="right">
                <q-btn flat label="Cancelar" color="negative" v-close-popup />
                <q-btn flat label="Generar" color="primary" @click="generateQuote" />
              </q-card-actions>
            </q-card>
          </q-dialog>

          <!-- Cotización generada -->
          <q-card-section v-if="quoteGenerated">
            <div class="text-h6">Cotización Generada</div>
            <div v-if="selectedQuoteType === 'all' || selectedQuoteType === 'materials'">
              <div class="text-subtitle2 q-mb-sm">Materiales:</div>
              <q-list separator>
                <q-item v-for="material in editableResults.materials" :key="material.material_id">
                  <q-item-section>
                    <q-item-label>{{ material.name }}</q-item-label>
                    <q-item-label caption>{{ material.application }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-item-label>{{ material.quantity }} {{ material.unit }}</q-item-label>
                    <q-item-label v-if="selectedQuoteType !== 'materials_list'" caption="${{ formatCurrency(material.total_cost) }}"></q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </div>
            <div v-if="selectedQuoteType === 'all' || selectedQuoteType === 'services'">
              <div class="text-subtitle2 q-mb-sm q-mt-md">Servicios:</div>
              <q-list separator>
                <q-item v-for="service in editableResults.services" :key="service.service_id">
                  <q-item-section>
                    <q-item-label>{{ service.name }}</q-item-label>
                    <q-item-label caption>{{ service.description }}</q-item-label>
                  </q-item-section>
                  <q-item-section side>
                    <q-item-label>{{ safeToFixed(service.quantity, 2) }} {{ service.unit }}</q-item-label>
                    <q-item-label caption="${{ formatCurrency(service.total_cost) }}"></q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </div>
            <q-separator class="q-my-md" />
            <div v-if="selectedQuoteType === 'all' || selectedQuoteType === 'materials'" class="row justify-between q-py-sm">
              <div>Subtotal Materiales:</div>
              <div class="text-weight-bold">{{ formatCurrency(editableResults.totals.materials_subtotal) }}</div>
            </div>
            <div v-if="selectedQuoteType === 'all' || selectedQuoteType === 'services'" class="row justify-between q-py-sm">
              <div>Subtotal Servicios:</div>
              <div class="text-weight-bold">{{ formatCurrency(editableResults.totals.services_subtotal) }}</div>
            </div>
            <q-separator class="q-my-sm" />
            <div class="row justify-between q-py-sm text-h6">
              <div>Total Estimado:</div>
              <div class="text-weight-bold text-primary">
                {{
                  selectedQuoteType === 'all' ? formatCurrency(editableResults.totals.total) :
                  selectedQuoteType === 'materials' ? formatCurrency(editableResults.totals.materials_subtotal) :
                  selectedQuoteType === 'materials_list' ? '' :
                  formatCurrency(editableResults.totals.services_subtotal)
                }}
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { useNotifications } from 'src/composables/useNotifications'
import { useGlobalLoader } from 'src/composables/useGlobalLoader'
import { useNumberFormatter } from 'src/composables/useNumberFormatter'

const router = useRouter()
const { showSuccess, showError, showInfo, showWarning } = useNotifications()
const { showLoader, hideLoader, withLoader } = useGlobalLoader()
const { safeToFixed, formatCurrency } = useNumberFormatter()

const form = ref({
  construction_type: '',
  length: 4,
  width: 4,
  height: 2.4
})

const constructionTypes = ref([])
const results = ref(null)
const editableResults = ref(null)
const loadingTypes = ref(false)
const calculating = ref(false)
const creatingQuote = ref(false)

const activeTab = ref('materials')
const showQuoteModal = ref(false)
const selectedQuoteType = ref('all')
const quoteGenerated = ref(false)

const quoteTypeOptions = [
  { label: 'Todo junto (materiales y mano de obra)', value: 'all' },
  { label: 'Solo materiales (con precio)', value: 'materials' },
  { label: 'Solo mano de obra', value: 'services' },
  { label: 'Solo materiales (sin precio)', value: 'materials_list' }
]

const constructionTypeOptions = computed(() => {
  return constructionTypes.value.map(type => ({
    label: type.name,
    value: type.name
  }))
})

// Sincronizar editableResults con results cada vez que results cambia
watch(results, (val) => {
  if (val) {
    editableResults.value = JSON.parse(JSON.stringify(val))
  }
}, { immediate: true })

function recalculateTotals() {
  if (!editableResults.value) return
  // Materiales
  let materialsSubtotal = 0
  editableResults.value.materials.forEach(mat => {
    mat.total_cost = Number(mat.quantity) * Number(mat.unit_cost)
    materialsSubtotal += mat.total_cost
  })
  // Servicios
  let servicesSubtotal = 0
  editableResults.value.services.forEach(serv => {
    serv.total_cost = Number(serv.quantity) * Number(serv.unit_cost)
    servicesSubtotal += serv.total_cost
  })
  // Totales
  editableResults.value.totals = {
    materials_subtotal: materialsSubtotal,
    services_subtotal: servicesSubtotal,
    total: materialsSubtotal + servicesSubtotal
  }
}

function restoreOriginals() {
  if (results.value) {
    editableResults.value = JSON.parse(JSON.stringify(results.value))
    recalculateTotals()
  }
}

const loadConstructionTypes = async () => {
  loadingTypes.value = true
  try {
    const response = await api.get('/construction-types')
    constructionTypes.value = response.data.data || response.data
    showSuccess('Tipos de construcción cargados correctamente')
  } catch (error) {
    console.error('Error cargando tipos de construcción:', error)
    showError('Error cargando tipos de construcción')
  } finally {
    loadingTypes.value = false
  }
}

const calculateConstruction = async () => {
  calculating.value = true
  showLoader('Calculando construcción...')
  try {
    const response = await api.post('/calculate-construction', form.value)
    results.value = response.data.data || response.data
    showSuccess('Cálculo completado exitosamente')
    quoteGenerated.value = false
  } catch (error) {
    console.error('Error en el cálculo:', error)
    showError('Error en el cálculo')
  } finally {
    calculating.value = false
    hideLoader()
  }
}

const generateQuote = () => {
  quoteGenerated.value = true
  showQuoteModal.value = false
}

onMounted(() => {
  loadConstructionTypes()
})
</script> 