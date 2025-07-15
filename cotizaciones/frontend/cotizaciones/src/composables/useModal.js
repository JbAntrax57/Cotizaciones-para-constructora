import { ref } from 'vue'

export function useModal(defaultData = {}) {
  const showDialog = ref(false)
  const editedItem = ref({ ...defaultData })
  const actionLoading = ref(false)
  const actionMessage = ref('')

  const openNewDialog = (clearData = defaultData) => {
    editedItem.value = { ...clearData }
    showDialog.value = true
  }

  const openEditDialog = (item) => {
    editedItem.value = { ...item }
    showDialog.value = true
  }

  const closeDialog = () => {
    showDialog.value = false
    editedItem.value = { ...defaultData }
  }

  const executeAction = async (action, successMessage, errorMessage) => {
    actionLoading.value = true
    actionMessage.value = editedItem.value.id ? 'Actualizando...' : 'Creando...'
    
    try {
      const response = await action()
      
      // Manejar respuesta estructurada del backend
      if (response && response.data) {
        const backendMessage = response.data.message
        if (backendMessage) {
          // Usar el mensaje del backend si está disponible
          return { success: true, message: backendMessage }
        }
      }
      
      closeDialog()
      return { success: true, message: successMessage || 'Operación exitosa' }
    } catch (error) {
      console.error(error)
      
      // Manejar errores de validación del backend
      if (error.response && error.response.data) {
        const errorData = error.response.data
        if (errorData.message) {
          return { success: false, message: errorData.message }
        }
        if (errorData.errors) {
          const errorMessages = Object.values(errorData.errors).flat()
          return { success: false, message: errorMessages.join(', ') }
        }
      }
      
      return { success: false, message: errorMessage || 'Error en la operación' }
    } finally {
      actionLoading.value = false
    }
  }

  return {
    showDialog,
    editedItem,
    actionLoading,
    actionMessage,
    openNewDialog,
    openEditDialog,
    closeDialog,
    executeAction
  }
} 