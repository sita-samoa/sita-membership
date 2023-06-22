<script setup>
import { reactive, ref, onMounted, onUpdated } from 'vue';

const props = defineProps({
  show: Boolean,
  duration: Number,
  text: String,
})
// const displayTooltip = ref(props.show)
// const startedAnimation = ref(false)
// console.log(displayTooltip);

// if(displayTooltip && !startedAnimation){
//   startedAnimation.value = true
//   setTimeout(() => {
//     startedAnimation.value = false
//     displayTooltip.value = false
//   }, props.duration)
// }

</script>
<template>
  <div class="relative">
    <div v-if="show" class="absolute w-auto px-2 py-1.5 rounded-md flex items-center h-5 -top-5 left-7 bg-slate-300">
      <span class="font-normal text-gray-800 text-xs">{{ text }}</span>
    </div>
    <slot></slot>
  </div>
</template>