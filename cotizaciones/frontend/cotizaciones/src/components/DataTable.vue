<template>
  <q-table
    :rows="rows"
    :columns="columns"
    :loading="loading"
    :pagination="pagination"
    row-key="id"
    :rows-per-page-options="[10, 25, 50, 100]"
    class="data-table"
  >
    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th
          v-for="col in props.cols"
          :key="col.name"
          :props="props"
          class="text-center"
        >
          {{ col.label }}
        </q-th>
      </q-tr>
    </template>
    
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td
          v-for="col in props.cols"
          :key="col.name"
          :props="props"
          class="text-center"
        >
          <template v-if="col.name === 'actions'">
            <slot name="actions" :row="props.row"></slot>
          </template>
          <template v-else-if="col.name === 'status'">
            <slot name="status" :row="props.row" :value="col.value"></slot>
          </template>
          <template v-else>
            {{ col.format ? col.format(col.value) : (col.value || '-') }}
          </template>
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'DataTable',
  props: {
    rows: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    const pagination = ref({
      sortBy: 'id',
      descending: false,
      page: 1,
      rowsPerPage: 25
    })

    return {
      pagination
    }
  }
}
</script>

<style scoped>
.data-table .q-table__top {
  padding: 8px 16px;
}

.data-table .q-table__bottom {
  padding: 8px 16px;
}
</style> 