export function useDateFormatter() {
  const formatDate = (dateString) => {
    if (!dateString) return '-'
    
    // Debug temporal
    console.log('Formatting date:', dateString, 'Type:', typeof dateString)
    
    // Si ya está en formato DD/MM/YYYY, devolverlo directamente
    if (typeof dateString === 'string' && dateString.includes('/') && dateString.split('/').length === 3) {
      return dateString
    }
    
    // Si ya es un objeto Date, usarlo directamente
    if (dateString instanceof Date) {
      return dateString.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      })
    }
    
    // Si es string, intentar parsearlo
    let date
    if (typeof dateString === 'string') {
      if (dateString.includes('T')) {
        // Formato ISO con tiempo
        date = new Date(dateString)
      } else if (dateString.includes('-')) {
        // Formato YYYY-MM-DD
        const [year, month, day] = dateString.split('-')
        date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day))
      } else {
        // Otros formatos
        date = new Date(dateString)
      }
    } else {
      date = new Date(dateString)
    }
    
    // Verificar si la fecha es válida
    if (isNaN(date.getTime())) {
      console.warn('Invalid date:', dateString)
      return '-'
    }
    
    return date.toLocaleDateString('es-ES', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    })
  }

  const formatDateTime = (dateString) => {
    if (!dateString) return '-'
    
    let date
    if (dateString instanceof Date) {
      date = dateString
    } else if (typeof dateString === 'string') {
      if (dateString.includes('T')) {
        date = new Date(dateString)
      } else if (dateString.includes('-')) {
        const [year, month, day] = dateString.split('-')
        date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day))
      } else {
        date = new Date(dateString)
      }
    } else {
      date = new Date(dateString)
    }
    
    if (isNaN(date.getTime())) {
      console.warn('Invalid date:', dateString)
      return '-'
    }
    
    return date.toLocaleDateString('es-ES', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  }

  const formatDateForInput = (dateString) => {
    if (!dateString) return ''
    
    let date
    if (dateString instanceof Date) {
      date = dateString
    } else if (typeof dateString === 'string') {
      // Si ya está en formato DD/MM/YYYY, convertirlo
      if (dateString.includes('/')) {
        const [day, month, year] = dateString.split('/')
        date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day))
      } else if (dateString.includes('T')) {
        date = new Date(dateString)
      } else if (dateString.includes('-')) {
        const [year, month, day] = dateString.split('-')
        date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day))
      } else {
        date = new Date(dateString)
      }
    } else {
      date = new Date(dateString)
    }
    
    if (isNaN(date.getTime())) {
      console.warn('Invalid date for input:', dateString)
      return ''
    }
    
    return date.toISOString().split('T')[0]
  }

  return {
    formatDate,
    formatDateTime,
    formatDateForInput
  }
} 