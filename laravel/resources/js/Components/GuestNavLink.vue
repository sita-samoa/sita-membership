<script setup>
import { computed } from 'vue'
import NavLink from '@/Components/NavLink.vue'

const props = defineProps({
  href: String,
  active: Boolean,
})

</script>

<template>
  <NavLink :href="href" :active="active" class="mr-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
    <slot />
  </NavLink>
</template>
