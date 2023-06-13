<script setup>
import { containerMaxW } from '@/config.js'
import BaseLevel from '@/Components/BaseLevel.vue'

const year = new Date().getFullYear()
</script>

<template>
  <footer class="py-2 px-6" :class="containerMaxW">
    <BaseLevel>
      <div class="text-center md:text-left text-sm">
        <b>&copy; {{ year }}, <a href="https://www.sita.ws/" target="_blank">Sāmoa IT Association</a>.</b>
        <slot />
      </div>
      <div class="md:py-2 text-sm">Developed by Sāmoa Stack Overflow.</div>
    </BaseLevel>
  </footer>
</template>
