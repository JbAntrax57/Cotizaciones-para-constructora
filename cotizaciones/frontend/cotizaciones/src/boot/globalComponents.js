// src/boot/globalComponents.js
import { boot } from 'quasar/wrappers'
import UppercaseInput from 'src/components/UppercaseInput.vue'

export default boot(({ app }) => {
  // Registrar componentes globales
  app.component('UppercaseInput', UppercaseInput)
}) 