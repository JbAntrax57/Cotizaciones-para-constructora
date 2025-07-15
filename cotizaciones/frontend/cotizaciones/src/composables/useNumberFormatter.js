export function useNumberFormatter() {
  const formatCurrency = (value, decimals = 2) => {
    if (value === null || value === undefined || isNaN(value)) {
      return `$${0.00.toFixed(decimals)}`
    }
    return `$${Number(value).toFixed(decimals)}`
  }

  const formatNumber = (value, decimals = 2) => {
    if (value === null || value === undefined || isNaN(value)) {
      return Number(0).toFixed(decimals)
    }
    return Number(value).toFixed(decimals)
  }

  const safeToFixed = (value, decimals = 2) => {
    if (value === null || value === undefined || isNaN(value)) {
      return Number(0).toFixed(decimals)
    }
    return Number(value).toFixed(decimals)
  }

  return {
    formatCurrency,
    formatNumber,
    safeToFixed
  }
} 