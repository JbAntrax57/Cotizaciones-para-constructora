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
   
      <q-dialog v-model="dialog" persistent @hide="closeDialog">
        <q-card style="min-width: 350px">
          <q-card-section>
            <div class="text-h6">{{ editingItem.id ? 'Editar' : 'Nuevo' }} Material</div>
          </q-card-section>
   
          <q-card-section>
            <q-form @submit="saveMaterial">
              <UppercaseInput 
                v-model="editingItem.name" 
                label="Nombre" 
                :rules="[val => !!val || 'Campo requerido']"
              />
              <UppercaseInput 
                v-model="editingItem.supplier" 
                label="Proveedor" 
                :rules="[val => !!val || 'Campo requerido']"
              />
              <UppercaseInput 
                v-model="editingItem.unit" 
                label="Unidad de Medida" 
                :rules="[val => !!val || 'Campo requerido']"
              />
              <q-input 
                v-model.number="editingItem.unit_cost" 
                type="number" 
                label="Costo Unitario" 
                prefix="$" 
                :rules="[val => val > 0 || 'Debe ser mayor a 0']"
              />
              <q-input 
                v-model="editingItem.description" 
                label="Descripción" 
                type="textarea" 
                :rules="[val => !!val || 'Campo requerido']"
              />
              
              <div class="row justify-end q-mt-md">
                <q-btn label="Cancelar" color="negative" v-close-popup :disable="loading" />
                <q-btn label="Guardar" type="submit" color="primary" class="q-ml-sm" :loading="loading" />
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
  import DataTable from 'src/components/DataTable.vue'
  import { useNotifications } from 'src/composables/useNotifications'
  import { useCrudOperations } from 'src/composables/useCrudOperations'
  import { useNumberFormatter } from 'src/composables/useNumberFormatter'
  import UppercaseInput from 'src/components/UppercaseInput.vue'
   
   export default {
    name: 'MaterialsPage',
    components: {
      DataTable,
      UppercaseInput
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
        loading: crudLoading,
        dialog,
        editingItem,
        openNewDialog,
        openEditDialog,
        closeDialog,
        saveItem,
        deleteItem
      } = useCrudOperations()

      const openNewMaterialDialog = () => {
        openNewDialog(defaultMaterialData)
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
          materials.value = response.data.data || response.data
        } catch (error) {
          console.error(error)
          showError('Error al cargar los materiales')
        } finally {
          loading.value = false
        }
      }
   
      const saveMaterial = async () => {
        const saveAction = async () => {
          if (editingItem.value.id) {
            return await api.put(`/materials/${editingItem.value.id}`, editingItem.value)
          } else {
            return await api.post('/materials', editingItem.value)
          }
        }

        const successMessage = editingItem.value.id ? 'Material actualizado correctamente' : 'Material agregado correctamente'
        
        await saveItem(saveAction, successMessage, loadMaterials)
      }

      const confirmDelete = async (material) => {
        const deleteAction = async () => {
          return await api.delete(`/materials/${material.id}`)
        }

        await deleteItem(deleteAction, material.name, loadMaterials)
      }
   
      loadMaterials()
   
      return {
        materials,
        columns,
        loading: crudLoading,
        dialog,
        editingItem,
        saveMaterial,
        editMaterial,
        confirmDelete,
        openNewMaterialDialog,
        closeDialog
      }
    }
   }
   </script>