<script setup>

defineProps({
  total: Number,
  from: Number,
  to: Number,
  itemText: {
    type: String,
    default: 'results'
  }
})

</script>
<template>

<div v-if="!total">No matches found. Try changing the filter.</div>
<div v-else class="mb-3">Showing {{ from }} to {{ to }} of {{ total }} {{ itemText }}.</div>

</template>
