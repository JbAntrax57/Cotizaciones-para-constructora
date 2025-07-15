<template>
  <!-- :rules="rules" -->
  <!-- :error="error" -->
  <q-select
    :class="{ 'field_color': fieldColor }"
    emit-value
    fill-input
    filled
    :hide-selected="!multiple"
    :label="label"
    map-options
    :multiple="multiple"
    :options="filteredOptions"
    :model-value="props.modelValue"
    :use-input="!multiple"
    @filter="filterOptions"
    @update:model-value="val => emit('update:modelValue', val)"
  >
    <template v-slot:prepend>
      <q-icon :name="icon" />
    </template>
    <template v-for="(_, slot) in $slots" v-slot:[slot]="scope">
      <slot :name="slot" v-bind="scope || {}" />
    </template>
    <template v-slot:option="{ itemProps, itemEvents, opt, selected, toggleOption }" v-if="multiple">
      <q-item v-bind="itemProps" v-on="{ ...itemEvents }">
        <q-item-section>
          <q-item-label>{{ opt.label }}</q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-toggle
            :value="selected"
            @update:model-value="toggleOption(opt)"
            :model-value="selected"
          />
        </q-item-section>
      </q-item>
    </template>
  </q-select>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: [Number, String, Array],
    default: null
  },
  label: {
    type: String,
    default: ''
  },
  options: {
    type: Array,
    required: true
  },
  icon: {
    type: String,
    default: ''
  },
  fieldColor: {
    type: Boolean,
    default: true
  },
  multiple: {
    type: Boolean,
    default: false
  }
  // error: {
  //   type: Boolean,
  //   default: false
  // }
})

const emit = defineEmits(['update:modelValue'])

// Eliminar selectedOption y su watcher
let label = ref(props.label)
let options = ref(props.options || [])
let icon = ref(props.icon)
let fieldColor = ref(props.fieldColor)
// let error = ref(props.error)

let filteredOptions = ref([...(options.value || [])])

// Eliminar watcher de modelValue

watch(() => props.label, (value) => {
  label.value = value
})

watch(() => props.options, (value) => {
  options.value = value || []
  filteredOptions.value = value || []
}, { deep: true })

// const rules = computed(() => {
//   if (props.error) {
//     return [
//       () => (!props.error) || 'El campo es requerido.'
//     ]
//   }
//   return false
// })

const filterOptions = (val, update) => {
  update(() => {
    const needle = val.toLowerCase()
    filteredOptions.value = (options.value || []).filter(v => v.label.toLowerCase().indexOf(needle) > -1)
  })
}
</script> 