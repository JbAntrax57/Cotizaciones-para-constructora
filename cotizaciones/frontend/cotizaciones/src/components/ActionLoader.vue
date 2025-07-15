<template>
  <div v-if="loading" class="action-loader-overlay">
    <q-card class="action-loader-card">
      <q-card-section class="text-center">
        <q-spinner-dots color="primary" size="50px" />
        <div class="text-h6 q-mt-md">{{ message }}</div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'ActionLoader',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    message: {
      type: String,
      default: 'Procesando...'
    }
  }
}
</script>

<style scoped>
.action-loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.action-loader-card {
  min-width: 300px;
  max-width: 400px;
}
</style> 