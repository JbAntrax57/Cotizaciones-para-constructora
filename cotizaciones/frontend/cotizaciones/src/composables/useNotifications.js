import { useQuasar } from 'quasar'

export function useNotifications() {
  const $q = useQuasar()

  const showSuccess = (message) => {
    $q.notify({
      type: 'positive',
      message: message,
      position: 'top',
      timeout: 3000,
      icon: 'check_circle'
    })
  }

  const showError = (message) => {
    $q.notify({
      type: 'negative',
      message: message,
      position: 'top',
      timeout: 5000,
      icon: 'error'
    })
  }

  const showInfo = (message) => {
    $q.notify({
      type: 'info',
      message: message,
      position: 'top',
      timeout: 3000,
      icon: 'info'
    })
  }

  const showWarning = (message) => {
    $q.notify({
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