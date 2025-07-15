export function useNumberFormatter() {
  const formatCurrency = (value, decimals = 2) => {
    const safeValue = Number.isFinite(Number(value)) ? Number(value) : 0
    const safeDecimals = Number.isFinite(Number(decimals)) ? Number(decimals) : 2
    return `$${safeValue.toLocaleString('es-MX', { minimumFractionDigits: safeDecimals, maximumFractionDigits: safeDecimals })}`
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