<script setup>
import { useForm } from '@inertiajs/vue3'
import DialogModal from '@/Components/DialogModal.vue'
import { FwbInput } from 'flowbite-vue'
import InputError from '@/Components/InputError.vue'
import CancelButton from '@/Components/CancelButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { stringifyExpression } from '@vue/compiler-core'

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
    default: 'Mark as Accepted',
  },
  buttonText: {
    type: String,
    default: 'Accept',
  },
})

const emits = defineEmits(['close'])

const form = useForm({
  financial_year: new Date().getFullYear(), // current year.
  receipt_number: null,
})

function accept() {
  form.put(route('members.accept', props.memberId), {
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
      <fwb-input v-model="form.financial_year" required placeholder="enter payment financial year" label="Financial Year" class="mb-2" type="number" />
      <InputError class="mt-2" :message="form.errors.financial_year" />

      <fwb-input v-model="form.receipt_number" placeholder="enter payment receipt #" label="Receipt #" class="mb-2" />
      <InputError class="mt-2" :message="form.errors.receipt_number" />
    </template>
    <template #footer>
      <CancelButton @click="emits('close')" />
      <div>
        <PrimaryButton :disabled="form.processing" @click="accept">{{ props.buttonText }}</PrimaryButton>
      </div>
    </template>
  </DialogModal>
</template>
