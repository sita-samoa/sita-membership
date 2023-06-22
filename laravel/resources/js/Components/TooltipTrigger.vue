<script setup>
import { ref } from 'vue';

const props = defineProps({
  duration: Number,
  text: String,
})

const emit = defineEmits(['trigger'])

const show = ref(false)

function showTooltip(){
  show.value = true
  emit('trigger')
  setTimeout(() => {
    show.value = false
  }, props.duration)
}

</script>
<template>
  <div class="relative">
    <div v-show="show" class="transition ease-linear absolute w-auto px-2 py-1.5 rounded-md flex items-center h-5 -top-5 left-7 bg-slate-300">
      <span class="font-normal text-black dark:text-gray-800 text-xs">{{ text }}</span>
    </div>
    <div @click="showTooltip">
      <slot></slot>
    </div>
  </div>
</template>