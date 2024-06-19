<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { FwbButton, FwbSelect } from 'flowbite-vue'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  member_id: Number,
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

const form = useForm({
  membership_status_id: props.status,
})

function submit() {
  form.put(route('members.membership-status.update', [props.member_id, form.membership_status_id]), {
    onSuccess() {
    },
  })
}
function resetForm() {
  form.membership_status_id = props.status
}

</script>
<template>
    
  <div class="my-4">
    <p>
      <fwb-button color="default" @click="isModalActive = true">Set Status</fwb-button>
    </p>
  </div>

  <CardBoxModal
    @confirm="submit"
    @cancel="resetForm"
    :disabled="form.processing"
    v-model="isModalActive"
    hasCancel
    buttonLabel="Save"
    title="Membership Status">
    <fwb-select
      v-model="form.membership_status_id"
      :options="options"
    />
  </CardBoxModal>

</template>