<script setup>
import { computed, ref } from 'vue'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import { Button, Progress, Input, Tabs, Tab } from 'flowbite-vue'
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
import InputError from '@/Components/InputError.vue'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue'
import DialogModal from '@/Components/DialogModal.vue'
import AddButton from '@/Components/AddButton.vue'
import DeleteButton from '@/Components/DeleteButton.vue'
import UpdateButton from '@/Components/UpdateButton.vue'
import CancelButton from '@/Components/CancelButton.vue'

const props = defineProps({
  checkable: {
    type: Boolean,
    default: false,
  },
  filters: Array,
  users: Object,
})

dayjs.extend(relativeTime)

const selectedUser = ref({})

const isModalDangerActive = ref(false)

const checkedRows = ref([])
const form = useForm({
  name: null,
  email: null,
  // password: null,
  // photo: null,
})

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

const listData = props.users
const itemId = ref(-1)

const showFormModal = ref(false)
const showConfirmationModal = ref(false)

const canAdd = computed(() => {
  return itemId.value < 0
})

const canEdit = computed(() => {
  return !canAdd.value
})

function closeModal() {
  showFormModal.value = false
}
function closeModalAndResetForm() {
  closeModal()
  itemId.value = -1
  form.reset()
  form.clearErrors()
}
function showModal() {
  showFormModal.value = true
}
function edit(item) {
  selectedUser.value = item
  itemId.value = selectedUser.value.id
  // let item = listData.find(i => i.id === id)

  form.name = selectedUser.value.name
  form.email = selectedUser.value.email
  showModal()
}
function submit() {
  form.post(route('users.store', itemId.value ), {
    onSuccess(res) {
      // let formCopy = Object.assign({}, form)
      // formCopy.id = res.props.flash.data.id
      // listData.push(formCopy)

      // // reset form
      // closeModalAndResetForm()
    },
  })
}
function update() {
  form.put(route('users.update', itemId.value ), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess() {
      // let item = listData.find(i => i.id === itemId.value)
      // item.qualification = form.qualification
      // item.year_attained = form.year_attained
      // item.institution = form.institution
      // item.country_iso2 = form.country_iso2

      // reset form
      closeModalAndResetForm()
    },
  })
}
function deleteItem() {
  form.delete(route('users.destroy', itemId.value), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess() {
      // for (var i = 0; i < listData.length; i++) {
      //   if (listData[i].id === itemId.value) {
      //     listData.splice(i, 1)
      //   }
      // }

      // // reset form
      // closeModalAndResetForm()
      // showConfirmationModal.value = false
    },
  })
}
</script>

<template>
  <DialogModal :show="showFormModal">
    <template #title>
      <div class="flex items-center text-lg">
        <span v-if="canAdd"> Add User </span>
        <span v-else> Edit User </span>
      </div>
    </template>
    <template #content>
      <form @submit.prevent="update">
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
          <Input v-model="form.name" label="Name" />
          <InputError class="mt-2" :message="form.errors.name" />
          <Input v-model="form.email" label="Email" />
          <InputError class="mt-2" :message="form.errors.email" />
          <Input v-model="form.password" type="password" autocomplete="new-password" label="Password" />
          <InputError class="mt-2" :message="form.errors.password" />
          <file-input v-model="form.photo" type="file" accept="image/*" label="Photo" />
        </div>
      </form>
    </template>
    <template #footer >
      <CancelButton @click="closeModalAndResetForm" />

      <div>
        <AddButton v-if="canAdd" :disabled="form.processing" @click="submit" />
        <DeleteButton v-if="canEdit" @click="showConfirmationModal = true" />
        <UpdateButton v-if="canEdit" @click="update" />
      </div>
    </template>
  </DialogModal>
  <DeleteConfirmationModal :show="showConfirmationModal" @delete="deleteItem" @close="showConfirmationModal = false" />

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
  <PaginationCount :from="props.users.from" :to="props.users.to" :total="props.users.total" itemText="users" />

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
      <tr v-for="client in props.users.data" :key="client.id">
        <TableCheckboxCell v-if="checkable" @checked="checked($event, client)" />
        <td data-label="Name">
          {{ client.name }}
        </td>
        <td data-label="Email">
          {{ client.email }}
        </td>
        <td data-label="Verified" class="lg:w-1 whitespace-nowrap">
          <small v-if="client.email_verified_at" class="text-gray-500 dark:text-slate-400" :title="client.email_verified_at">{{ dayjs(client.email_verified_at).fromNow() }}</small>
        </td>
        <td data-label="Created" class="lg:w-1 whitespace-nowrap">
          <small class="text-gray-500 dark:text-slate-400" :title="client.created_at">{{ dayjs(client.created_at).fromNow() }}</small>
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton color="info" :icon="mdiEye" small @click="edit(client)" />
            <BaseButton color="danger" :icon="mdiTrashCan" small @click="isModalDangerActive = true" />
          </BaseButtons>
        </td>
      </tr>
    </tbody>
  </table>

  <Pagination :links="props.users.links" />
</template>
