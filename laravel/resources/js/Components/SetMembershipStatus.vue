<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import { FwbSelect, FwbInput } from 'flowbite-vue'
import DialogModal from '@/Components/DialogModal.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import BaseButton from '@/Components/BaseButton.vue'
import InputError from '@/Components/InputError.vue'

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
  financial_year: new Date().getFullYear(), // current year.
  receipt_number: null,
})

function submit() {
  // Submit if form.membership_status_id value changes
  if (form.membership_status_id != props.status) {
    form.put(route('members.membership-status.update', [props.member_id, form.membership_status_id]), {
      onSuccess() {
        isModalActive.value = false
        resetForm()
      }
    })
  }
  else {
    // Otherwise, just close the modal
    isModalActive.value = false
  }
}
function resetForm() {
  form.membership_status_id = props.status
  form.financial_year = new Date().getFullYear(), // current year.
  form.receipt_number = null
}

</script>

<template>

  <BaseButton label="Update Membership Status..." small color="info" @click="isModalActive = true" />

  <DialogModal
    @close="isModalActive = false"
    :show="isModalActive"
    >
    <template #title>
      Update Membership Status
    </template>
    <template #content>

      <form @submit.prevent="submit" :disabled="form.processing">
        <fwb-select class="mb-2"
          label="Membership Status"
          v-model="form.membership_status_id"
          :options="options"
        />

        <!-- Acceptance Details -->
        <div v-if="form.membership_status_id == 4">
          <div class="mb-3">Please provide the following information.</div>
          <fwb-input v-model="form.financial_year" required placeholder="enter payment financial year" label="Financial Year" class="mb-2" type="number" />
          <InputError class="mt-2" :message="form.errors.financial_year" />

          <fwb-input v-model="form.receipt_number" placeholder="enter payment receipt #" label="Receipt #" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.receipt_number" />
        </div>

        <BaseButtons class="mt-4" type="justify-between">
          <BaseButton label="Update" color="info" type="submit" :disabled="form.processing" />
          <BaseButton label="Cancel" color="default" outline @click="isModalActive = false" />
        </BaseButtons>
      </form>
    </template>
  </DialogModal>

</template>
