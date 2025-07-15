import { ref } from 'vue'
import { useNotifications } from './useNotifications'

export function useCrudOperations() {
  const loading = ref(false)
  const dialog = ref(false)
  const editingItem = ref({})
  const { showSuccess, showError } = useNotifications()

  const openNewDialog = (defaultData = {}) => {
    editingItem.value = { ...defaultData }
    dialog.value = true
  }

  const openEditDialog = (item) => {
    editingItem.value = { ...item }
    dialog.value = true
  }

  const closeDialog = () => {
    dialog.value = false
    editingItem.value = {}
  }

  const executeAction = async (action) => {
    loading.value = true
    try {
      const result = await action()
      return { success: true, data: result }
    } catch (error) {
      console.error('Error en operación CRUD:', error)
      const errorMessage = error.response?.data?.message || error.message || 'Error en la operación'
      showError(errorMessage)
      return { success: false, message: errorMessage }
    } finally {
      loading.value = false
    }
  }

  const saveItem = async (saveAction, successMessage, refreshCallback) => {
    const result = await executeAction(saveAction)
    
    if (result.success) {
      showSuccess(successMessage)
      closeDialog()
      if (refreshCallback) {
        await refreshCallback()
      }
    }
    
    return result
  }

  const deleteItem = async (deleteAction, itemName, refreshCallback) => {
    const result = await executeAction(deleteAction)
    
    if (result.success) {
      showSuccess(`${itemName} eliminado correctamente`)
      if (refreshCallback) {
        await refreshCallback()
      }
    }
    
    return result
  }

  return {
    loading,
    dialog,
    editingItem,
    openNewDialog,
    openEditDialog,
    closeDialog,
    executeAction,
    saveItem,
    deleteItem
  }
}

// Composable para manejar múltiples instancias de CRUD
export function useMultipleCrud() {
  const createCrudInstance = () => {
    return useCrudOperations()
  }

  return {
    createCrudInstance
  }
} 