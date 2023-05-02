<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  links: {
    type: Array,
    default: []
  }
})

</script>
<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key"
          class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded"
          v-html="link.label" />
        <Link v-else :key="`link-${key}`"
          class="mb-1 mr-1 px-4 py-3 rounded block hover:border border-blue-700 focus:text-indigo-500 text-sm leading-4"
          :class="{ 'bg-blue-700 text-white': link.active }" :href="link.url"
          v-html="link.label" />
    </template>
  </div>
</div>
</template>
