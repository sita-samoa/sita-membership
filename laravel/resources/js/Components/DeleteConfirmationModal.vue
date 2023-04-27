<script setup>
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import { Button } from "flowbite-vue";

const emit = defineEmits(["close", "delete"]);

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
});
</script>
<template>
  <ConfirmationModal :show="show">
    <template v-slot:title> Delete </template>
    <template v-slot:content> Are you sure? </template>
    <template v-slot:footer>
      <Button color="alternative" class="mr-3" @click="emit('close')"
        >Cancel</Button
      >
      <Button color="red" @click="emit('delete')">Do it</Button>
    </template>
  </ConfirmationModal>
</template>
