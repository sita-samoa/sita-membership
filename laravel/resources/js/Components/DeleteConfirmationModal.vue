<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import { Button } from 'flowbite-vue'

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
      <Button color="alternative" class="mr-3" @click="emit('close')">Cancel</Button>
      <Button color="red" @click="emit('delete')">Do it</Button>
    </template>
  </ConfirmationModal>
</template>
