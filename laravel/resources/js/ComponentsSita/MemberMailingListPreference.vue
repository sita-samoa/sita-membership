<script setup>
import { useForm } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import { Alert, Button } from 'flowbite-vue'
import { computed } from 'vue'

const props = defineProps({
  member_id: Number,
  mailing_options: {
    type: Object,
    default: [],
  },
  list: {
    type: Object,
    default: [],
  },
})

const form = useForm({
  mailing_list_id: null,
  subscribe: null,
})

const defaultOptions = props.mailing_options.map(e => ({ ...e, subscribed: false }))
const hasExistingMailingOptions = props.list.length > 0

const memberPreferences = computed(() => {
  return defaultOptions.map(e => {
    let pref = props.list.find(x => x.mailing_list_id === e.id)
    if (pref) {
      return {
        ...e,
        subscribed: pref.subscribed,
      }
    }
    return e
  })
})

const mailingOptions = hasExistingMailingOptions ? memberPreferences : defaultOptions

function toggleSubscription(event) {
  const mailing_list_id = event.target.value
  const subscribe = event.target.checked
  form.mailing_list_id = mailing_list_id
  form.subscribe = subscribe
  form.put(route('members.subscribe', { member: props.member_id }), {
    preserveScroll: true,
    resetOnSuccess: false,
  })
  form.reset()
}
</script>
<template>
  <div>
    <InputLabel value="Mailing Lists" class="mb-4" />
    <Alert v-if="!hasExistingMailingOptions" type="info" class="mt-3 mb-2">Please check the mail lists you want to join. </Alert>

    <form @submit.prevent="$emit('submit', 'viewed_mailing_list')">
      <div v-for="m in mailingOptions" class="flex items-center mb-4">
        <input :id="m.id" type="checkbox" :checked="m.subscribed" :value="m.id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" @change="toggleSubscription" />
        <InputLabel :for="m.id" :value="m.title" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" />
      </div>
      <!-- next button -->
      <Button v-show="$page.props.user.permissions.canUpdate" type="submit" class="p-3 mt-3">Next</Button>
    </form>
  </div>
</template>
