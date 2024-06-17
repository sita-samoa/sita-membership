<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import { FwbButton } from 'flowbite-vue'

const emit = defineEmits(['close', 'delete'])

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
})
</script>
<template>
  <ConfirmationModal :show="show">
    <template #title> Delete </template>
    <template #content> Are you sure? </template>
    <template #footer>
      <fwb-button color="alternative" class="mr-3" @click="emit('close')">Cancel</fwb-button>
      <fwb-button color="red" @click="emit('delete')">Do it</fwb-button>
    </template>
  </ConfirmationModal>
</template>
