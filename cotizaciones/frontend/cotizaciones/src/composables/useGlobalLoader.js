import { useQuasar } from 'quasar'

export function useGlobalLoader() {
  const $q = useQuasar()

  const showLoader = (message = 'Cargando...') => {
    if ($q && $q.loading) {
      $q.loading.show({
        message: message,
        spinnerColor: 'primary',
        spinnerSize: 140
      })
    }
  }

  const hideLoader = () => {
    if ($q && $q.loading) {
      $q.loading.hide()
    }
  }

  const withLoader = async (promise, message = 'Cargando...') => {
    try {
      showLoader(message)
      const result = await promise
      return result
    } finally {
      hideLoader()
    }
  }

  return {
    showLoader,
    hideLoader,
    withLoader
  }
} 