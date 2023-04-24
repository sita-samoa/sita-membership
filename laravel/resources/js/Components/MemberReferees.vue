<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { Alert, Button, Input } from 'flowbite-vue'
import DialogModal from './DialogModal.vue';
import DeleteConfirmationModal from './DeleteConfirmationModal.vue';
import InputError from './InputError.vue'
import { ref } from 'vue'
import MemberRefereesList from './MemberRefereesList.vue';

const props = defineProps({
  member_id: Number,
  list: {
    type: Object,
    default: []
  }
})

const showFormModal = ref(false)
const showConfirmationModal = ref(false)

function closeModal() {
  showFormModal.value = false
}

function closeModalAndResetForm(){
  closeModal();
  itemId.value = -1
  form.reset();
}

function showModal() {
  showFormModal.value = true
}


const refereesList = props.list;
console.log(refereesList)
const itemId = ref(-1);

const form = useForm({
  'name': '',
  'organisation': '',
  'phone': '',
  'email': ''
});

function edit(id){
  itemId.value = id;
  const item = refereesList.find(x => x.id === id);
  form.name = item.name;
  form.organisation = item.organisation;
  form.phone = item.phone;
  form.email = item.email;
  showModal();
}

function update(id){
  form.put(route('members.referees.update', { member: props.member_id, referee: itemId.value}), {
    onSuccess(res) {
      let item = refereesList.find(x => x.id === itemId.value);
      item.name = form.name;
      item.organisation = form.organisation;
      item.phone = form.phone;
      item.email = form.email;

      // reset form
      closeModalAndResetForm()
    }
  })
}

function deleteReferee(id){
  form.delete(route('members.referees.destroy', { member: props.member_id, referee: itemId.value }), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess: function(){
      let item = refereesList.find(x => x.id === itemId.value);
      refereesList.splice(refereesList.indexOf(item),1)
      closeModalAndResetForm();
      showConfirmationModal.value = false;
    }
  })
}

function submit(){
  form.post(route('members.referees.store', props.member_id), {
    onSuccess(res) {
      let formCopy = Object.assign({}, form)
      formCopy.id = res.props.flash.data.id
      refereesList.push(formCopy)

      // reset form
      closeModalAndResetForm()
    }
  })
}
</script>
<template>
<div>
  <h5>Referees</h5>
  <Alert type="info" class="mb-2 mt-3">Please provide details for someone
    how can verify roles and responsibilities.
  </Alert>

  <Link href="#">
    <Button class="p-3 mt-3" color="alternative" @click.prevent="showModal" >Add Referee</Button>
  </Link>
  <MemberRefereesList :list="refereesList" @edit-item="edit"/>
</div>

<!-- Modal -->
<DialogModal :show="showFormModal" @close="closeModalAndResetForm">
  <template #title>
    <div class="flex items-center text-lg">
      <span v-if="itemId < 0">
        Add Referee
      </span>
      <span v-else>
        Edit Referee
      </span>
    </div>
  </template>
  <template #content>
    <Input v-model="form.name" placeholder="enter your referees name" label="Name" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.name" />

    <Input v-model="form.organisation" placeholder="enter your referees organisation" label="Organisation" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.organisation" />

    <Input v-model="form.phone" placeholder="enter your referees phone" label="Phone" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.phone" />

    <Input v-model="form.email" placeholder="enter your referees email" label="Email" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.email" />

  </template>
  <template #footer>
      <button @click="closeModalAndResetForm" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
        Cancel
      </button>
      <div>
        <button v-if="itemId < 0" @click="submit" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          Add
        </button>
        <button v-if="itemId > 0" @click="showConfirmationModal = true" type="button" class="mr-3 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
          Delete
        </button>
        <button v-if="itemId > 0" @click="update" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          Update
        </button>
    </div>
  </template>
</DialogModal>
<DeleteConfirmationModal :show="showConfirmationModal" @delete="deleteReferee" @close="showConfirmationModal=false" />
</template>
