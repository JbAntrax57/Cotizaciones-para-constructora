# Componente GlobalSelect

Este componente proporciona una interfaz estandarizada para todos los selects en la aplicación, con funcionalidades como filtrado, selección múltiple y validación.

## Características

- ✅ Filtrado automático de opciones
- ✅ Soporte para selección múltiple
- ✅ Iconos personalizables
- ✅ Validación integrada
- ✅ Estilo consistente con el tema
- ✅ Emisión de eventos para cambios

## Props

| Prop | Tipo | Default | Descripción |
|------|------|---------|-------------|
| `modelValue` | Number/String/Array | null | Valor seleccionado |
| `label` | String | '' | Etiqueta del campo |
| `options` | Array | required | Array de opciones `[{label, value}]` |
| `icon` | String | '' | Icono a mostrar |
| `fieldColor` | Boolean | true | Aplicar color de campo |
| `multiple` | Boolean | false | Habilitar selección múltiple |

## Eventos

| Evento | Parámetros | Descripción |
|--------|------------|-------------|
| `update:modelValue` | value | Emitido cuando cambia la selección |

## Uso Básico

```vue
<template>
  <GlobalSelect
    v-model="selectedValue"
    :options="options"
    label="Seleccionar opción"
    icon="person"
  />
</template>

<script>
export default {
  setup() {
    const selectedValue = ref(null)
    const options = ref([
      { label: 'Opción 1', value: 1 },
      { label: 'Opción 2', value: 2 }
    ])

    return {
      selectedValue,
      options
    }
  }
}
</script>
```

## Selección Múltiple

```vue
<template>
  <GlobalSelect
    v-model="selectedValues"
    :options="options"
    label="Seleccionar múltiples"
    icon="list"
    :multiple="true"
  />
</template>

<script>
export default {
  setup() {
    const selectedValues = ref([])
    const options = ref([
      { label: 'Servicio 1', value: 1 },
      { label: 'Servicio 2', value: 2 }
    ])

    return {
      selectedValues,
      options
    }
  }
}
</script>
```

## Con Validación

```vue
<template>
  <q-form @submit="onSubmit">
    <GlobalSelect
      v-model="selectedValue"
      :options="options"
      label="Campo requerido"
      icon="star"
      :rules="[val => !!val || 'Campo requerido']"
    />
    <q-btn label="Enviar" type="submit" />
  </q-form>
</template>
```

## Con Eventos

```vue
<template>
  <GlobalSelect
    v-model="selectedValue"
    :options="options"
    label="Con eventos"
    @update:modelValue="onChange"
  />
</template>

<script>
export default {
  setup() {
    const selectedValue = ref(null)
    
    const onChange = (value) => {
      console.log('Nuevo valor:', value)
    }

    return {
      selectedValue,
      onChange
    }
  }
}
</script>
```

## Formato de Opciones

Las opciones deben tener este formato:

```javascript
const options = [
  { label: 'Texto visible', value: 'valor_real' },
  { label: 'Cliente A', value: 1 },
  { label: 'Servicio B', value: 'servicio_b' }
]
```

## Integración con Backend

Para usar con datos del backend:

```javascript
const loadOptions = async () => {
  try {
    const response = await api.get('/clients')
    // Transformar datos del backend al formato requerido
    options.value = response.data.data.map(client => ({
      label: client.business_name,
      value: client.id
    }))
  } catch (error) {
    console.error('Error cargando opciones:', error)
  }
}
```

## Estilos

El componente usa las clases CSS de Quasar y se adapta automáticamente al tema claro/oscuro. El prop `fieldColor` controla si se aplica el color de campo del tema.

## Notas

- El componente usa `emit-value` y `map-options` por defecto
- El filtrado es case-insensitive
- Para selección múltiple, usa toggles en lugar de checkboxes
- Los slots están disponibles para personalización avanzada 