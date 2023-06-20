<script setup>
import { ref } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import { mdiEye, mdiTrashCan } from '@mdi/js'
import CardBoxModal from '@/Components/CardBoxModal.vue'
import TableCheckboxCell from '@/Components/TableCheckboxCell.vue'
import BaseLevel from '@/Components/BaseLevel.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import BaseButton from '@/Components/BaseButton.vue'
import Pagination from '@/Components/Pagination.vue'
import PaginationCount from '@/Components/PaginationCount.vue'

defineProps({
  checkable: {
    type: Boolean,
    default: false,
  },
  filters: Array,
  users: Object,
})

dayjs.extend(relativeTime)

const isModalActive = ref(false)
const selectedUser = ref({})

const isModalDangerActive = ref(false)

const checkedRows = ref([])

const remove = (arr, cb) => {
  const newArr = []

  arr.forEach(item => {
    if (!cb(item)) {
      newArr.push(item)
    }
  })

  return newArr
}

const checked = (isChecked, client) => {
  if (isChecked) {
    checkedRows.value.push(client)
  } else {
    checkedRows.value = remove(checkedRows.value, row => row.id === client.id)
  }
}

function showModal(user) {
  isModalActive.value = true
  selectedUser.value = user
}
</script>

<template>
  <CardBoxModal v-model="isModalActive" :title=selectedUser.name>
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <CardBoxModal v-model="isModalDangerActive" title="Please confirm" button="danger" has-cancel>
    <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
    <p>This is sample modal</p>
  </CardBoxModal>

  <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
    <span v-for="checkedRow in checkedRows" :key="checkedRow.id" class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700">
      {{ checkedRow.name }}
    </span>
  </div>

  <!-- No results message -->
  <PaginationCount :from="users.from" :to="users.to" :total="users.total" itemText="users" />

  <table class="mb-3">
    <thead>
      <tr>
        <th v-if="checkable" />
        <th>Name</th>
        <th>Email</th>
        <th>Verified</th>
        <th>Created</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="client in users.data" :key="client.id">
        <TableCheckboxCell v-if="checkable" @checked="checked($event, client)" />
        <td data-label="Name">
          {{ client.name }}
        </td>
        <td data-label="Email">
          {{ client.email }}
        </td>
        <td data-label="Verified" class="lg:w-1 whitespace-nowrap">
          <small class="text-gray-500 dark:text-slate-400" :title="client.email_verified_at">{{ dayjs(client.email_verified_at).fromNow() }}</small>
        </td>
        <td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small class="text-gray-500 dark:text-slate-400" :title="client.created_at">{{ dayjs(client.created_at).fromNow() }}</small>
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton color="info" :icon="mdiEye" small @click="showModal(client)" />
            <BaseButton color="danger" :icon="mdiTrashCan" small @click="isModalDangerActive = true" />
          </BaseButtons>
        </td>
      </tr>
    </tbody>
  </table>

  <Pagination :links="users.links" />
</template>
