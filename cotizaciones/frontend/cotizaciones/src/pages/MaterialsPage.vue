<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Materiales</h5>
        <q-btn color="primary" icon="add" label="Nuevo Material" @click="openNewMaterialDialog" />
      </div>
   
      <DataTable
        :rows="materials"
        :columns="columns"
        :loading="loading"
      >
        <template #actions="{ row }">
          <q-btn flat round color="primary" icon="edit" @click="editMaterial(row)" />
          <q-btn flat round color="negative" icon="delete" @click="confirmDelete(row)" />
        </template>
      </DataTable>
   
      <q-dialog v-model="showDialog" persistent @hide="closeDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">{{ editedMaterial.id ? 'Editar' : 'Nuevo' }} Material</div>
          </q-card-section>
   
          <q-card-section>
            <q-form @submit="saveMaterial">
                          <q-input v-model="editedMaterial.name" label="Nombre" :rules="[val => !!val || 'Campo requerido']" />
            <q-input v-model="editedMaterial.supplier" label="Proveedor" :rules="[val => !!val || 'Campo requerido']" />
            <q-input v-model="editedMaterial.unit" label="Unidad de Medida" :rules="[val => !!val || 'Campo requerido']" />
            <q-input v-model.number="editedMaterial.unit_cost" type="number" label="Costo Unitario" prefix="$" />
            <q-input v-model="editedMaterial.description" label="Descripción" type="textarea" :rules="[val => !!val || 'Campo requerido']" />
              
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
     import { ref } from 'vue'
  import { api } from 'boot/axios'
  import DataTable from 'src/components/DataTable.vue'
  import ActionLoader from 'src/components/ActionLoader.vue'
  import { useNotifications } from 'src/composables/useNotifications'
  import { useModal } from 'src/composables/useModal'
  import { useNumberFormatter } from 'src/composables/useNumberFormatter'
   
   export default {
    name: 'MaterialsPage',
    components: {
      DataTable,
      ActionLoader
    },
    setup() {
      const materials = ref([])
      const loading = ref(false)

      const defaultMaterialData = {
        name: '',
        supplier: '',
        unit: '',
        unit_cost: 0,
        description: ''
      }

      const { showSuccess, showError } = useNotifications()
      const { formatCurrency } = useNumberFormatter()
      const { 
        showDialog, 
        editedItem: editedMaterial, 
        actionLoading, 
        actionMessage,
        openNewDialog,
        openEditDialog,
        executeAction,
        closeDialog
      } = useModal(defaultMaterialData)

      const openNewMaterialDialog = () => {
        openNewDialog()
      }

      const editMaterial = (material) => {
        openEditDialog(material)
      }
   
      const columns = [
        { name: 'name', label: 'Nombre', field: 'name', sortable: true },
        { name: 'supplier', label: 'Proveedor', field: 'supplier' },
        { name: 'unit', label: 'Unidad', field: 'unit' },
        { name: 'unit_cost', label: 'Costo Unitario', field: 'unit_cost', format: formatCurrency },
        { name: 'description', label: 'Descripción', field: 'description' },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]
   
      const loadMaterials = async () => {
        loading.value = true
        try {
          const response = await api.get('/materials')
          // Acceder a response.data.data porque el backend ahora devuelve { success, message, data }
          materials.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los materiales')
        } finally {
          loading.value = false
        }
      }
   
      const saveMaterial = async () => {
        const result = await executeAction(async () => {
          if (editedMaterial.value.id) {
            return await api.put(`/materials/${editedMaterial.value.id}`, editedMaterial.value)
          } else {
            return await api.post('/materials', editedMaterial.value)
          }
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadMaterials()
        } else {
          showError(result.message)
        }
      }

      const confirmDelete = async (material) => {
        const result = await executeAction(async () => {
          return await api.delete(`/materials/${material.id}`)
        })
        
        if (result.success) {
          showSuccess(result.message)
          loadMaterials()
        } else {
          showError(result.message)
        }
      }
   
      loadMaterials()
   
      return {
        materials,
        columns,
        loading,
        actionLoading,
        actionMessage,
        showDialog,
        editedMaterial,
        saveMaterial,
        editMaterial,
        confirmDelete,
        openNewMaterialDialog,
        closeDialog
      }
    }
   }
   </script>