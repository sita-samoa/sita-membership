<script setup>
defineEmits(['click'])
</script>
<template>
  <button type="button" class="mr-3 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" @click="$emit('click')">Delete</button>
</template>
