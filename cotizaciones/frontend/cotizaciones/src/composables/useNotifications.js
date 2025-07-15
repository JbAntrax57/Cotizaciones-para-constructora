import { Notify } from 'quasar'

export function useNotifications() {
  const showSuccess = (message) => {
    Notify.create({
      type: 'positive',
      message: message,
      position: 'top',
      timeout: 3000,
      icon: 'check_circle'
    })
  }

  const showError = (message) => {
    Notify.create({
      type: 'negative',
      message: message,
      position: 'top',
      timeout: 5000,
      icon: 'error'
    })
  }

  const showInfo = (message) => {
    Notify.create({
      type: 'info',
      message: message,
      position: 'top',
      timeout: 3000,
      icon: 'info'
    })
  }

  const showWarning = (message) => {
    Notify.create({
      type: 'warning',
      message: message,
      position: 'top',
      timeout: 4000,
      icon: 'warning'
    })
  }

  return {
    showSuccess,
    showError,
    showInfo,
    showWarning
  }
}

// Composable para manejo de inputs con uppercase
export function useInputUtils() {
  const toUpperCase = (value) => {
    return value ? value.toString().toUpperCase() : ''
  }

  const handleInputBlur = (event) => {
    if (event.target.value) {
      event.target.value = event.target.value.toUpperCase()
    }
  }

  return {
    toUpperCase,
    handleInputBlur
  }
} 