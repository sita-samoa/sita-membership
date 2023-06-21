<script setup>
import { onMounted } from 'vue'
import { initDropdowns } from 'flowbite'

// initialize components based on data attribute selectors
onMounted(() => {
  initDropdowns()
})

defineEmits(['update:modelValue', 'reset'])

defineProps({
  modelValue: String,
  maxWidth: {
    type: Number,
    default: 300,
  },
  displayText: {
    type: String,
    default: 'Filter',
  },
  placeholder: {
    type: String,
    default: 'Search',
  },
})
</script>
<template>
  <div class="flex mb-3">
    <button id="dropdown-button" data-dropdown-toggle="dropdown" class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" type="button">
      {{ displayText }} <svg aria-hidden="true" class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
    </button>
    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
      <slot />
    </div>
    <div class="relative w-full">
      <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
      </div>
      <input id="search-dropdown" :value="modelValue" type="search" autocomplete="off" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="placeholder" required @input="$emit('update:modelValue', $event.target.value)" />
    </div>
    <!-- Reset button -->
    <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm" type="button" @click="$emit('reset')">Reset</button>
  </div>
</template>
