<script setup>
import { useForm } from '@inertiajs/vue3';
import { Button, Input } from 'flowbite-vue'
import { computed, ref } from 'vue'
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import MemberQualificationsList from '@/Components/MemberQualificationsList.vue';
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue';
import DialogModal from './DialogModal.vue';
import AddButton from '@/Components/AddButton.vue';
import DeleteButton from '@/Components/DeleteButton.vue';
import UpdateButton from '@/Components/UpdateButton.vue';
import CancelButton from '@/Components/CancelButton.vue';

const props = defineProps({
  member_id: Number,
  list: {
    type: Object,
    default: []
  },
  editable: {
    type: Boolean,
    default: true
  },
})

const form = useForm({
  country_id: null,
  qualification: null,
  year_attained: null,
  institution: null,
})

const listData = props.list
const itemId = ref(-1)

const countryOptions = [
  { id: 1, name: "Australia" },
  { id: 2, name: "Fiji" },
  { id: 3, name: "New Zealand" },
  { id: 4, name: "Samoa" },
  { id: 5, name: "United States of America" },
]
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

  form.qualification = item.qualification
  form.year_attained = item.year_attained
  form.institution = item.institution
  form.country_id = item.country_id
  showModal()
}
function submit() {
  form.post(route('members.qualifications.store', props.member_id), {
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
  form.put(route('members.qualifications.update', { member: props.member_id, qualification: itemId.value }), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess() {
      let item = listData.find(i => i.id === itemId.value)
      item.qualification = form.qualification
      item.year_attained = form.year_attained
      item.institution = form.institution
      item.country_id = form.country_id

      // reset form
      closeModalAndResetForm()
    }
  })
}
function deleteItem() {
  form.delete(route('members.qualifications.destroy', { member: props.member_id, qualification: itemId.value }), {
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
  <h5 class="mb-3">Academic Qualifications</h5>

  <Button v-show="props.editable" class="p-3 mb-3" color="alternative" @click.prevent="showModal" >Add Qualification</Button>

  <!-- Member qualifications list -->
  <MemberQualificationsList :list="listData" @edit-item="edit" />
</div>

<!-- Modal -->
<DialogModal :show="showFormModal" @close="closeModalAndResetForm">
  <template #title>
    <div class="flex items-center text-lg">
      <span v-if="canAdd">
        Add Qualification
      </span>
      <span v-else>
        Edit Qualification
      </span>
    </div>
  </template>
  <template #content>
    <Input v-model="form.qualification" placeholder="enter your qualification" label="Qualification" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.qualification" />

    <Input name="year_attained" type="number" v-model="form.year_attained" placeholder="enter your year attended" label="Year attended" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.year_attained" />

    <Input v-model="form.institution" placeholder="enter your institution" label="Institution" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.institution" />

    <InputLabel for="countries" value="Country" class="mb-2" />
    <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-3">
      <option selected>Choose a country</option>
      <option v-for="c in countryOptions" :value="c.id">{{ c.name }}</option>
    </select>
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
