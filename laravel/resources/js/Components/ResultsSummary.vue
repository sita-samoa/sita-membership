<script setup>
defineProps({
  total: Number,
  from: Number,
  to: Number,
})
</script>
<template>
  <div v-if="!total">No matches found. Try changing the filter.</div>
  <div v-else class="mb-3">
    <span v-if="total === 1"> Showing 1 result. </span>
    <span v-else> Showing {{ from }} to {{ to }} of {{ total }} results. </span>
  </div>
</template>
