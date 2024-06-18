<script setup>
import { ref } from 'vue'
import { FwbButton } from 'flowbite-vue'
import CardBoxModal from '@/Components/CardBoxModal.vue'

const isModalActive = ref(false)

</script>
<template>
    
    <div class="my-4">
      <p>
        <fwb-button color="default" @click="isModalActive = true">Set Status</fwb-button>
      </p>
    </div>

    <CardBoxModal v-model="isModalActive" hasCancel buttonLabel="Save" title="Set Membership Status">
        <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
        <p>This is sample modal</p>
  </CardBoxModal>

</template>