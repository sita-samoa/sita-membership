<script setup>
import { useForm } from '@inertiajs/vue3';
import { Alert, Button, Input, Progress } from 'flowbite-vue'
import { computed, ref } from 'vue'
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import MemberDocumentsList from '@/Components/MemberDocumentsList.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue';
import DialogModal from '@/Components/DialogModal.vue';
import AddButton from '@/Components/AddButton.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import UpdateButton from '@/Components/UpdateButton.vue';
import CancelButton from '@/Components/CancelButton.vue';

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
  file_name: null,
})

const listData = props.list
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
}
function showModal() {
  showFormModal.value = true
}
function edit(id) {
  itemId.value = id
  let item = listData.find(i => i.id === id)
  form.title = item.title
  form.file_name = item.file_name

  showModal()
}
function submit() {
  form.post(route('members.documents.store', props.member_id), {
    onSuccess(res) {
      let item = Object.assign({}, form)
      item.id = res.props.flash.data.id
      item.file_name = res.props.flash.data.file_name
      item.file_size = res.props.flash.data.file_size
      listData.push(item)

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
    <Input v-model="form.title" :placeholder="form.file_name ? form.file_name : 'enter your title'" label="Title" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.title" />

    <span v-if="canAdd" >
      <InputLabel for="file" value="File Upload" class="mb-4" />
      <input type="file" id="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" @input="form.file = $event.target.files[0]" />
      <InputError class="mt-2" :message="form.errors.file" />

      <Progress v-if="form.progress" :progress="form.progress.percentage">
        {{ form.progress.percentage }}%
      </Progress>
    </span>

  </template>
  <template #footer>
    <CancelButton @click="closeModalAndResetForm" />

    <div>
      <AddButton v-if="canAdd" @click="submit" />
      <DeleteButton v-if="canEdit" @click="showConfirmationModal = true" />
      <UpdateButton v-if="canEdit" @click="update" />
    </div>
  </template>
</DialogModal>
<DeleteConfirmationModal :show="showConfirmationModal" @delete="deleteItem" @close="showConfirmationModal=false" />
</template>
