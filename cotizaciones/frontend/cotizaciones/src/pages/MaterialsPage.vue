<template>
    <q-page padding>
      <div class="row items-center justify-between q-mb-md">
        <h5 class="q-my-none">Materiales</h5>
        <q-btn color="primary" icon="add" label="Nuevo Material" @click="showDialog = true" />
      </div>
   
      <q-table
        :rows="materials"
        :columns="columns"
        row-key="id"
        :loading="loading"
      >
        <template v-slot:body-cell-actions="props">
          <q-td :props="props">
            <q-btn flat round color="primary" icon="edit" @click="editMaterial(props.row)" />
            <q-btn flat round color="negative" icon="delete" @click="confirmDelete(props.row)" />
          </q-td>
        </template>
      </q-table>
   
      <q-dialog v-model="showDialog">
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
   import { ref } from 'vue'
   import { api } from 'boot/axios'
   
   export default {
    name: 'MaterialsPage',
    setup() {
      const materials = ref([])
      const loading = ref(false)
      const showDialog = ref(false)
      const editedMaterial = ref({
        name: '',
        supplier: '',
        unit: '',
        unit_cost: 0
      })
   
      const columns = [
        { name: 'name', label: 'Nombre', field: 'name', sortable: true },
        { name: 'supplier', label: 'Proveedor', field: 'supplier' },
        { name: 'unit', label: 'Unidad', field: 'unit' },
        { name: 'unit_cost', label: 'Costo Unitario', field: 'unit_cost', format: val => `$${val.toFixed(2)}` },
        { name: 'actions', label: 'Acciones', field: 'actions' }
      ]
   
      const loadMaterials = async () => {
        loading.value = true
        try {
          const response = await api.get('/materials')
          materials.value = response.data
        } catch (error) {
          console.error(error)
        } finally {
          loading.value = false
        }
      }
   
      const saveMaterial = async () => {
        try {
          if (editedMaterial.value.id) {
            await api.put(`/materials/${editedMaterial.value.id}`, editedMaterial.value)
          } else {
            await api.post('/materials', editedMaterial.value)
          }
          showDialog.value = false
          loadMaterials()
        } catch (error) {
          console.error(error)
        }
      }
   
      loadMaterials()
   
      return {
        materials,
        columns,
        loading,
        showDialog,
        editedMaterial,
        saveMaterial
      }
    }
   }
   </script>