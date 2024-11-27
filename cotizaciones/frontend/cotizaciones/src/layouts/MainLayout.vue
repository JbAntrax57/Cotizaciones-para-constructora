<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" @click="toggleLeftDrawer" />
        <q-toolbar-title>Sistema de Cotizaciones</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item to="/" exact>
          <q-item-section avatar><q-icon name="dashboard" /></q-item-section>
          <q-item-section>Dashboard</q-item-section>
        </q-item>

        <q-item to="/clients">
          <q-item-section avatar><q-icon name="people" /></q-item-section>
          <q-item-section>Clientes</q-item-section>
        </q-item>

        <q-item to="/projects">
          <q-item-section avatar><q-icon name="work" /></q-item-section>
          <q-item-section>Proyectos</q-item-section>
        </q-item>

        <q-item to="/services">
          <q-item-section avatar><q-icon name="build" /></q-item-section>
          <q-item-section>Servicios</q-item-section>
        </q-item>

        <q-item to="/materials">
          <q-item-section avatar><q-icon name="inventory" /></q-item-section>
          <q-item-section>Materiales</q-item-section>
        </q-item>

        <q-item to="/quotes">
          <q-item-section avatar><q-icon name="description" /></q-item-section>
          <q-item-section>Cotizaciones</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref } from 'vue'

export default {
  setup () {
    const leftDrawerOpen = ref(false)
    return {
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  }
}
</script>