<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" @click="toggleLeftDrawer" />
        <q-toolbar-title>Sistema de Cotizaciones</q-toolbar-title>
        
        <!-- Botón para alternar modo oscuro -->
        <q-btn
          flat
          round
          dense
          :icon="Dark.isActive ? 'light_mode' : 'dark_mode'"
          @click="toggleDark"
          :color="Dark.isActive ? 'yellow' : 'grey-8'"
          class="q-ml-sm"
        />
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

        <q-separator />

        <q-item to="/calculator">
          <q-item-section avatar><q-icon name="calculate" /></q-item-section>
          <q-item-section>Calculadora Automática</q-item-section>
        </q-item>

        <q-item to="/construction-type-config">
          <q-item-section avatar><q-icon name="settings" /></q-item-section>
          <q-item-section>Configuración de Tipos</q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import { Dark } from 'quasar'

const leftDrawerOpen = ref(false)

const toggleLeftDrawer = () => {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

const toggleDark = () => {
  Dark.toggle()
}
</script>