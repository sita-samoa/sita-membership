<script setup>
import { useForm } from '@inertiajs/vue3'
import DialogModal from '@/Components/DialogModal.vue'
import { FwbInput } from 'flowbite-vue'
import InputError from '@/Components/InputError.vue'
import CancelButton from '@/Components/CancelButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  memberId: {
    type: Number,
    default: 0,
  },
  headingText: {
    type: String,
    default: 'Membership Application Rejection',
  },
  buttonText: {
    type: String,
    default: 'Reject',
  },
})

const emits = defineEmits(['close'])

const form = useForm({
  reason: '',
})

function reject() {
  form.put(route('members.reject', props.memberId), {
    resetOnSuccess: false,
    onSuccess() {
      emits('close')
    },
  })
}
</script>
<template>
  <DialogModal :show="show">
    <template #title>{{ props.headingText }}</template>
    <template #content>
      <div class="mb-3">Please provide the following information.</div>
      <fwb-input v-model="form.reason" required placeholder="enter reason" label="Reason" class="mb-2" type="text" />
      <InputError class="mt-2" :message="form.errors.reason" />
    </template>
    <template #footer>
      <CancelButton @click="emits('close')" />
      <div>
        <PrimaryButton class="bg-red-500 hover:bg-red-400" :disabled="form.processing" @click="reject">{{ props.buttonText }}</PrimaryButton>
      </div>
    </template>
  </DialogModal>
</template>
