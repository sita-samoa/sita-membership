<script setup>
import { ref } from 'vue'
import { FwbButton, FwbSelect } from 'flowbite-vue'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  membershipStatuses: {
    type: Object,
    required: true,
  },
  status: {
    type: Number,
    required: true,
  },
})

const isModalActive = ref(false)
const options = usePage().props.membershipStatuses.map(item => ({
  name: item.title,
  value: item.id
}));

</script>
<template>
    
    <div class="my-4">
      <p>
        <fwb-button color="default" @click="isModalActive = true">Set Status</fwb-button>
      </p>
    </div>

    <CardBoxModal v-model="isModalActive" hasCancel buttonLabel="Save" title="Membership Status">
      <fwb-select
        v-model="props.status"
        :options="options"
      />
  </CardBoxModal>

</template>