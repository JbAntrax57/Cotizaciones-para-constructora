<template>
  <q-input
    v-model="inputValue"
    v-bind="$attrs"
    @blur="handleBlur"
    @input="handleInput"
  />
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'UppercaseInput',
  inheritAttrs: false,
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    uppercase: {
      type: Boolean,
      default: true
    }
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    const inputValue = computed({
      get() {
        return props.modelValue
      },
      set(value) {
        emit('update:modelValue', value)
      }
    })

    const handleBlur = (event) => {
      if (props.uppercase && event.target.value) {
        const upperValue = event.target.value.toUpperCase()
        emit('update:modelValue', upperValue)
      }
    }

    const handleInput = (value) => {
      if (props.uppercase && value) {
        emit('update:modelValue', value.toUpperCase())
      }
    }

    return {
      inputValue,
      handleBlur,
      handleInput
    }
  }
}
</script> 