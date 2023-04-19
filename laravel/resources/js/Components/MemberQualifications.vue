<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { Button, Modal, Input } from 'flowbite-vue'
import { ref } from 'vue'
import InputLabel from './InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps([
  'member_id',
  'list'
])

const form = useForm({
  'country_id': '',
  'qualification': '',
  'year_attained': '',
  'institution': '',
})

const countryOptions = [
  { id: 1, name: "Australia" },
  { id: 2, name: "Fiji" },
  { id: 3, name: "New Zealand" },
  { id: 4, name: "Samoa" },
  { id: 5, name: "United States of America" },
]
const isShowModal = ref(false)

function closeModal() {
  isShowModal.value = false
}
function showModal() {
  isShowModal.value = true
}
function submit() {
  form.post(route('members.qualifications.store', props.member_id), {
    preserveScroll: true,
    resetOnSuccess: false,
    onSuccess() {
      console.log('success')
      closeModal()
    }
  })
}
</script>
<template>
<div>
  <h5>Academic Qualifications</h5>
  member_id: {{  props.member_id }}
  <Link href="#">
    <Button class="p-3 mt-3" color="alternative" @click.prevent="showModal" >Add Qualification</Button>

    <!-- Member qualifications list -->
    <div v-for="l in props.list">
      {{ l.id }}
    </div>
  </Link>
</div>

<!-- Modal -->
<Modal :size="size" v-if="isShowModal" @close="closeModal">
  <template #header>
    <div class="flex items-center text-lg">
      Add Qualification
    </div>
  </template>
  <template #body>
    <Input v-model="form.qualification" placeholder="enter your qualification" label="Qualification" class="mb-2" />
    <InputError class="mt-2" :message="form.errors.qualification" />

    <Input v-model="form.year_attained" placeholder="enter your year attended" label="Year attended" class="mb-2" />
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
    <div class="flex justify-between">
      <button @click="closeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
        Cancel
      </button>
      <button @click="submit" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Add
      </button>
    </div>
  </template>
</Modal>
</template>
