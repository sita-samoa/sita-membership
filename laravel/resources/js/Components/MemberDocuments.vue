<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { Alert, Button, Input } from 'flowbite-vue'
import { computed, ref } from 'vue'
import InputError from '@/Components/InputError.vue';
import MemberDocumentsList from '@/Components/MemberDocumentsList.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue';
import DialogModal from './DialogModal.vue';

const props = defineProps({
  member_id: Number,
  list: {
    type: Object,
    default: []
  }
})

const form = useForm({
  title: '',
  file: null,
})

const listData = props.list
const itemId = ref(-1)

const showFormModal = ref(false)
const showConfirmationModal = ref(false)
const canAdd = computed(() => {
  return itemId.value < 0
})

function closeModal() {
  showFormModal.value = false
}
function closeModalAndResetForm() {
  closeModal()
  itemId.value = -1
  form.reset()
}
function showModal() {
  showFormModal.value = true
}
function edit(id) {
  itemId.value = id
  let item = listData.find(i => i.id === id)

  form.title = item.title
  showModal()
}
function submit() {
  form.post(route('members.documents.store', props.member_id), {
    onSuccess(res) {
      let formCopy = Object.assign({}, form)
      formCopy.id = res.props.flash.data.id
      listData.push(formCopy)

      // reset form
      closeModalAndResetForm()
    }
  })
}
function update() {
  form.put(route('members.documents.update', { member: props.member_id, document: itemId.value }), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess(res) {
      let item = listData.find(i => i.id === itemId.value)
      item.title = form.title
      item.file_name = res.props.flash.data.file_name
      item.file_size = res.props.flash.data.file_size

      // reset form
      closeModalAndResetForm()
    }
  })
}
function deleteItem() {
  form.delete(route('members.documents.destroy', { member: props.member_id, document: itemId.value }), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess() {

      for (var i=0; i< listData.length; i++) {
        if (listData[i].id === itemId.value) {
          listData.splice(i, 1)
        }
      }

      // reset form
      closeModalAndResetForm()
      showConfirmationModal.value = false
    }
  })
}
</script>
<template>
<div>
  <Alert type="info" class="my-2">Please attach certified copies of
    Supporting documents.
  </Alert>
  <h5>Supporting Documents</h5>

  <Button class="p-3 my-3" color="alternative" @click.prevent="showModal" >Add Qualification</Button>

  <!-- Member supporting documents list -->
  <MemberDocumentsList :list="listData" @edit-item="edit" />
</div>

<!-- Modal -->
<DialogModal :show="showFormModal" @close="closeModalAndResetForm">
  <template #title>
    <div class="flex items-center text-lg">
      <span v-if="canAdd">
        Add Supporting Document
      </span>
      <span v-else>
        Edit Supporting Document
      </span>
    </div>
  </template>
  <template #content>
    <Input v-model="form.title" placeholder="enter your title" label="Title" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.title" />

    <input v-if="canAdd" type="file" @input="form.file = $event.target.files[0]" />
    <InputError class="mt-2" :message="form.errors.file" />

    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
      {{ form.progress.percentage }}%
    </progress>

  </template>
  <template #footer>
      <button @click="closeModalAndResetForm" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
        Cancel
      </button>

      <div>
        <button v-if="canAdd" @click="submit" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          Add
        </button>
        <button v-if="!canAdd" @click="showConfirmationModal = true" type="button" class="mr-3 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
          Delete
        </button>
        <button v-if="!canAdd" @click="update" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          Update
        </button>
    </div>
  </template>
</DialogModal>
<DeleteConfirmationModal :show="showConfirmationModal" @delete="deleteItem" @close="showConfirmationModal=false" />
</template>
