
<script setup>
import { ref } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import DialogModal from '@/Components/DialogModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'

dayjs.extend(relativeTime)

defineProps({
  auditLog: {
    type: Object,
    default: {}
  },
})

const show = ref(false)
const selectedItem = ref(null)

function showModal(item) {
  show.value = true
  selectedItem.value = item
}

</script>
<template>

  <div class="relative overflow-x-auto" v-if="auditLog.length">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                  <th scope="col" class="px-6 py-3">
                      Event
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Date
                  </th>
                  <th scope="col" class="px-6 py-3 hidden md:block">
                      Fields
                  </th>
                  <th scope="col" class="px-6 py-3">
                      By
                  </th>
              </tr>
          </thead>
          <tbody>
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                v-for="a in auditLog">
                  <th scope="row" class="capitalize px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                      <a href="#" @click="showModal(a)" class="underline text-indigo-500"> {{ a.event }} </a>
                  </th>
                  <td class="px-6 py-4">
                      {{ dayjs(a.created_at).fromNow() }}
                  </td>
                  <td class="px-6 py-4 hidden md:block">
                      <div v-for="(a, index) in Object.keys(a.new_values)">{{ a }}</div>
                  </td>
                  <td class="px-6 py-4">
                      {{ a.user.name }}
                  </td>
              </tr>
          </tbody>
      </table>
  </div>

<DialogModal :show="show" >
  <template #title><span class="capitalize">{{ selectedItem.event }} - {{ dayjs(selectedItem.created_at).fromNow() }} (id: {{ selectedItem.id }})</span></template>
  <template #content>

      <!-- mobile -->
      <div class="visible md:hidden">
        <div class="px-5 py-3 bg-white border-b dark:bg-gray-800 dark:border-gray-700"
            v-for="(a, index) in Object.keys(selectedItem.new_values)">
            <p scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white mb-3">
                Field: {{ a }}
            </p>
            <p>
                Old Value: {{ selectedItem.old_values[a] }}
            </p>
            <p>
                New Value: {{ selectedItem.new_values[a] }}
            </p>
        </div>
      </div>

      <!-- desktop -->
      <div class="hidden md:block">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-6 py-3">
                  Field
              </th>
              <th scope="col" class="px-6 py-3">
                  Old value
              </th>
              <th scope="col" class="px-6 py-3">
                  New value
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
              v-for="(a, index) in Object.keys(selectedItem.new_values)">
              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ a }}
              </th>
              <td class="px-6 py-4">
                  {{ selectedItem.old_values[a] }}
              </td>
              <td class="px-6 py-4">
                  {{ selectedItem.new_values[a] }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- content footer -->
      <div class="my-3 ml-5">
        <div class="mb-3"><InputLabel>User</InputLabel> {{ selectedItem.user.name }}</div>
        <div class="mb-3"><InputLabel>Model</InputLabel> {{ selectedItem.auditable_type }}</div>
        <div class="mb-3"><InputLabel>IP address</InputLabel> {{ selectedItem.ip_address }}</div>
        <div class="mb-3"><InputLabel>Url</InputLabel> {{ selectedItem.url }}</div>
        <div class="mb-3"><InputLabel>User Agent</InputLabel> {{ selectedItem.user_agent }}</div>
      </div>
  </template>
  <template #footer>
    <SecondaryButton @click="show = false">Close</SecondaryButton>
  </template>
</DialogModal>
</template>

