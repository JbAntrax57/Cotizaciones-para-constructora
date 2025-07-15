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

      <!-- Resultados -->
      <div class="col-12 col-md-6" v-if="results">
        <q-card>
          <q-card-section>
            <div class="text-h6">Resultados del Cálculo</div>
          </q-card-section>

          <q-card-section>
            <!-- Dimensiones calculadas -->
            <div class="text-subtitle2 q-mb-md">Dimensiones:</div>
            <div class="row q-col-gutter-sm q-mb-md">
              <div class="col-6">
                <q-chip color="blue" text-color="white">
                  Área: {{ safeToFixed(results.dimensions.floor_area, 2) }} m²
                </q-chip>
              </div>
              <div class="col-6">
                <q-chip color="green" text-color="white">
                  Perímetro: {{ safeToFixed(results.dimensions.perimeter, 2) }} m
                </q-chip>
              </div>
            </div>

            <!-- Materiales -->
            <div class="text-subtitle2 q-mb-sm">Materiales Necesarios:</div>
            <q-list separator>
              <q-item v-for="material in results.materials" :key="material.material_id">
                <q-item-section>
                  <q-item-label>{{ material.name }}</q-item-label>
                  <q-item-label caption>{{ material.application }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-item-label>{{ material.quantity }} {{ material.unit }}</q-item-label>
                  <q-item-label caption>${{ safeToFixed(material.total_cost, 2) }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>

            <!-- Servicios -->
            <div class="text-subtitle2 q-mb-sm q-mt-md">Servicios:</div>
            <q-list separator>
              <q-item v-for="service in results.services" :key="service.service_id">
                <q-item-section>
                  <q-item-label>{{ service.name }}</q-item-label>
                  <q-item-label caption>{{ service.description }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <q-item-label>{{ safeToFixed(service.quantity, 2) }} {{ service.unit }}</q-item-label>
                  <q-item-label caption>${{ safeToFixed(service.total_cost, 2) }}</q-item-label>
                </q-item-section>
              </q-item>
            </q-list>

            <!-- Totales -->
            <q-separator class="q-my-md" />
            <div class="row justify-between q-py-sm">
              <div>Subtotal Materiales:</div>
              <div class="text-weight-bold">${{ safeToFixed(results.totals.materials_subtotal, 2) }}</div>
            </div>
            <div class="row justify-between q-py-sm">
              <div>Subtotal Servicios:</div>
              <div class="text-weight-bold">${{ safeToFixed(results.totals.services_subtotal, 2) }}</div>
            </div>
            <q-separator class="q-my-sm" />
            <div class="row justify-between q-py-sm text-h6">
              <div>Total Estimado:</div>
              <div class="text-weight-bold text-primary">${{ safeToFixed(results.totals.total, 2) }}</div>
            </div>

            <!-- Botón para crear cotización -->
            <q-btn
              label="Crear Cotización"
              color="positive"
              class="full-width q-mt-md"
              @click="createQuote"
              :loading="creatingQuote"
            />
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios'
import { useNotifications } from 'src/composables/useNotifications'
import { useGlobalLoader } from 'src/composables/useGlobalLoader'
import { useNumberFormatter } from 'src/composables/useNumberFormatter'

const router = useRouter()
const { showSuccess, showError, showInfo, showWarning } = useNotifications()
const { showLoader, hideLoader, withLoader } = useGlobalLoader()
const { safeToFixed } = useNumberFormatter()

const form = ref({
  construction_type: '',
  length: 4,
  width: 4,
  height: 2.4
})

const constructionTypes = ref([])
const results = ref(null)
const loadingTypes = ref(false)
const calculating = ref(false)
const creatingQuote = ref(false)

// Opción computada para GlobalSelect
const constructionTypeOptions = computed(() => {
  return constructionTypes.value.map(type => ({
    label: type.name,
    value: type.name
  }))
})

const loadConstructionTypes = async () => {
  loadingTypes.value = true
  try {
    const response = await api.get('/construction-types')
    // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
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
    // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
    results.value = response.data.data || response.data
    showSuccess('Cálculo completado exitosamente')
  } catch (error) {
    console.error('Error en el cálculo:', error)
    showError('Error en el cálculo')
  } finally {
    calculating.value = false
    hideLoader()
  }
}

const createQuote = () => {
  creatingQuote.value = true
  showLoader('Creando cotización...')
  
  // Simular creación de cotización
  setTimeout(() => {
    showInfo('Función de crear cotización próximamente')
    creatingQuote.value = false
    hideLoader()
  }, 1000)
}

onMounted(() => {
  loadConstructionTypes()
})
</script> 