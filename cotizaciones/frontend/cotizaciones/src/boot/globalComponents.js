// src/boot/globalComponents.js
import { boot } from 'quasar/wrappers'
import UppercaseInput from 'src/components/UppercaseInput.vue'
import GlobalSelect from 'src/components/GlobalSelect.vue'

export default boot(({ app }) => {
  // Registrar componentes globales
  app.component('UppercaseInput', UppercaseInput)
  app.component('GlobalSelect', GlobalSelect)
}) 