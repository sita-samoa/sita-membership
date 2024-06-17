<script setup>
import { computed, ref, watch } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import { Input, Button } from 'flowbite-vue'
import debounce from 'lodash/debounce'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import { mdiPencil, mdiTrashCan } from '@mdi/js'
import TableCheckboxCell from '@/Components/TableCheckboxCell.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import BaseButton from '@/Components/BaseButton.vue'
import Pagination from '@/Components/Pagination.vue'
import PaginationCount from '@/Components/PaginationCount.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue'
import DialogModal from '@/Components/DialogModal.vue'
import AddButton from '@/Components/AddButton.vue'
import DeleteButton from '@/Components/DeleteButton.vue'
import UpdateButton from '@/Components/UpdateButton.vue'
import CancelButton from '@/Components/CancelButton.vue'
import TextInput from '@/Components/TextInput.vue'
import FormSection from '@/Components/FormSection.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import UserAvatar from '@/Components/UserAvatar.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import BaseLevel from '@/Components/BaseLevel.vue'
import CardBox from '@/Components/CardBox.vue'

const props = defineProps({
  checkable: {
    type: Boolean,
    default: false,
  },
  filters: Object,
  users: Object,
  availableRoles: Array,
})

dayjs.extend(relativeTime)

const selectedUser = ref({})

const isModalDangerActive = ref(false)

const checkedRows = ref([])
const form = useForm({
  _method: 'PUT',
  name: null,
  email: null,
  photo: null,
  password: null,
  role: null,
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
function showEditForm(item) {
  selectedUser.value = item
  itemId.value = selectedUser.value.id

  form.name = selectedUser.value.name
  form.email = selectedUser.value.email
  form.photo = selectedUser.value.photo
  form.role = selectedUser.value.role?.key

  if (selectedUser.profile_photo_path) {
    photoPreview.value = profile_photo_path
  }
  showModal()
}
function showDeleteForm(item) {
  selectedUser.value = item
  itemId.value = selectedUser.value.id

  showConfirmationModal.value = true
}
function hideDeleteForm() {
  itemId.value = -1
  showConfirmationModal.value = false
}
function submit() {
  form._method = ''
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }
  form.post(route('users.store', itemId.value), {
    onSuccess() {
      clearPhotoFileInput()

      // reset form
      closeModalAndResetForm()
    },
  })
}
function update() {
  form._method = 'PUT'
  if (photoInput.value) {
    form.photo = photoInput.value.files[0]
  }

  form.post(route('users.update', itemId.value), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess() {
      clearPhotoFileInput()

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
      showConfirmationModal.value = false
      // reset form
      closeModalAndResetForm()
    },
  })
}

const photoPreview = ref(null)
const photoInput = ref(null)

const selectNewPhoto = () => {
  photoInput.value.click()
}

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0]

  if (!photo) return

  const reader = new FileReader()

  reader.onload = e => {
    photoPreview.value = e.target.result
  }

  reader.readAsDataURL(photo)
}

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null
      clearPhotoFileInput()
    },
  })
}

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null
  }
}

const searchForm = useForm({
  search: usePage().props.filters.search,
  role: usePage().props.filters.role,
  limit: usePage().props.filters.limit,
})

function reset() {
  searchForm.search = ''
  searchForm.role = ''
  searchForm.limit = usePage().props.filters.limit
}

watch(
  () => searchForm,
  debounce(function () {
    searchForm.get(route('users.index'), {
      search: searchForm.search,
      role: searchForm.role,
      limit: searchForm.limit,
    })
  }, 500),
  { deep: true }
)
</script>

<template>
  <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
    <span v-for="checkedRow in checkedRows" :key="checkedRow.id" class="inline-block px-2 py-1 mr-2 text-sm bg-gray-100 rounded-sm dark:bg-slate-700">
      {{ checkedRow.name }}
    </span>
  </div>

  <!-- Search filter -->
  <BaseLevel class="mb-3">
    <SearchFilter v-model="searchForm.search" @reset="reset" class="mr-3">
      <div class="col-span-6 m-6 sm:col-span-4">
        <InputLabel for="role" value="Role" />
        <select id="role" v-model="searchForm.role" class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
          <option :value="null" />
          <option v-for="role in props.availableRoles" :value="role.key">{{ role.name }}</option>
        </select>
      </div>
    </SearchFilter>

    <BaseButton type="submit" color="info" @click.prevent="showModal" label="Add User" />
  </BaseLevel>

  <!-- No results message -->
  <PaginationCount :from="props.users.from" :to="props.users.to" :total="props.users.total" itemText="users" />

  <CardBox class="mb-3">
    <table class="mb-3">
      <thead>
        <tr>
          <th v-if="checkable" />
          <th v-if="checkable" />
          <th />
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Verified</th>
          <th>Created</th>
          <th />
        </tr>
      </thead>
      <tbody>
        <tr v-for="client in props.users.data" :key="client.id">
          <TableCheckboxCell v-if="checkable" @checked="checked($event, client)" />
          <td class="border-b-0 lg:w-6 before:hidden">
            <UserAvatar :username="client.name" :avatar="client.profile_photo_url" class="w-24 h-24 mx-auto lg:w-6 lg:h-6" />
          </td>
          <td data-label="Name">
            {{ client.name }}
          </td>
          <td data-label="Email">
            {{ client.email }}
          </td>
          <td data-label="Role">
            {{ client.role?.name }}
          </td>
          <td data-label="Verified" class="lg:w-1 whitespace-nowrap">
            <small v-if="client.email_verified_at" class="text-gray-500 dark:text-slate-400" :title="client.email_verified_at">{{ dayjs(client.email_verified_at).fromNow() }}</small>
          </td>
          <td data-label="Created" class="lg:w-1 whitespace-nowrap">
            <small class="text-gray-500 dark:text-slate-400" :title="client.created_at">{{ dayjs(client.created_at).fromNow() }}</small>
          </td>
          <td class="before:hidden lg:w-1 whitespace-nowrap">
            <BaseButtons type="justify-start lg:justify-end" no-wrap>
              <BaseButton color="info" :icon="mdiPencil" small @click="showEditForm(client)" />
              <BaseButton color="danger" :icon="mdiTrashCan" small @click="showDeleteForm(client)" />
            </BaseButtons>
          </td>
        </tr>
      </tbody>
    </table>
  </CardBox>
  <div class="my-8 grid grid-cols-3 gap-1 justify-evenly">
    <div class="rounded-lg h-12 grid grid-cols-3 gap-1">
      <label for="limit" class="py-2 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Items per page:</label>

      <select v-model="searchForm.limit" id="limit" class="mb-1 ml-2 px-4 py-3 text-gray-400 text-sm leading-4 border rounded bg-gray-50 border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>
    <div class="col-span-2 rounded-lg h-12">
      <Pagination :links="props.users.links" />
    </div>
  </div>

  <DialogModal :show="showFormModal">
    <template #title>
      <div class="flex items-center text-lg">
        <span v-if="canAdd"> Add User </span>
        <span v-else> Edit User </span>
      </div>
    </template>
    <template #content>
      <FormSection @submitted="update">
        <template #title> Profile Information </template>

        <template #description> Update the account's profile information and email address. </template>

        <template #form>
          <!-- Profile Photo -->
          <div class="col-span-6 sm:col-span-4">
            <!-- Profile Photo File Input -->
            <input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview" />

            <InputLabel for="photo" value="Photo" />

            <!-- Current Profile Photo -->
            <div v-show="!photoPreview" class="mt-2">
              <img :src="selectedUser.profile_photo_url" :alt="selectedUser.name" class="object-cover w-20 h-20 rounded-full" />
            </div>

            <!-- New Profile Photo Preview -->
            <div v-show="photoPreview" class="mt-2">
              <span class="block w-20 h-20 bg-center bg-no-repeat bg-cover rounded-full" :style="'background-image: url(\'' + photoPreview + '\');'" />
            </div>

            <SecondaryButton class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto"> Select A New Photo </SecondaryButton>

            <SecondaryButton v-if="selectedUser.profile_photo_path" type="button" class="mt-2" @click.prevent="deletePhoto"> Remove Photo </SecondaryButton>

            <InputError :message="form.errors.photo" class="mt-2" />
          </div>
          <!-- Name -->
          <div class="col-span-6 sm:col-span-4">
            <InputLabel for="name" value="Name" />
            <TextInput id="name" v-model="form.name" type="text" class="block w-full mt-1" autocomplete="name" />
            <InputError :message="form.errors.name" class="mt-2" />
          </div>

          <!-- Email -->
          <div class="col-span-6 sm:col-span-4">
            <InputLabel for="email" value="Email" />
            <TextInput id="email" v-model="form.email" type="email" class="block w-full mt-1" autocomplete="username" />
            <InputError :message="form.errors.email" class="mt-2" />
          </div>

          <!-- Password -->
          <div class="col-span-6 sm:col-span-4">
            <InputLabel for="password" value="Password" />
            <TextInput id="password" v-model="form.password" type="password" class="block w-full mt-1" autocomplete="current-password" />
            <InputError :message="form.errors.password" class="mt-2" />
          </div>

          <!-- Role -->
          <div class="col-span-6 sm:col-span-4">
            <InputLabel for="role" value="Role" />
            <select id="role" v-model="form.role" class="w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
              <option value="-1" />
              <option v-for="role in props.availableRoles" :value="role.key">{{ role.name }}</option>
            </select>
          </div>
        </template>
      </FormSection>
    </template>
    <template #footer>
      <CancelButton @click="closeModalAndResetForm" />

      <div>
        <AddButton v-if="canAdd" :disabled="form.processing" @click="submit" />
        <DeleteButton v-if="canEdit" @click="showConfirmationModal = true" />
        <UpdateButton v-if="canEdit" @click="update" />
      </div>
    </template>
  </DialogModal>

  <DeleteConfirmationModal :show="showConfirmationModal" @delete="deleteItem" @close="hideDeleteForm" />
</template>
