<script setup>
import { Link } from '@inertiajs/vue3';
import { Alert, Button, Modal, Input } from 'flowbite-vue'
import { ref } from 'vue'

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
</script>
<template>
<div>
  <h5>Work Experience</h5>

  <Link href="#">
    <Button class="p-3 mt-3" color="alternative" @click.prevent="showModal" >Add Work Experience</Button>
  </Link>
</div>

<!-- Modal -->
<Modal :size="size" v-if="isShowModal" @close="closeModal">
  <template #header>
    <div class="flex items-center text-lg">
      Add Work Experience
    </div>
  </template>
  <template #body>
    <Input placeholder="enter your organisation" label="Organisation" class="mb-2" />
    <Input placeholder="enter your position" label="Position" class="mb-2" />

    <Alert type="info" class="mb-2 mt-3">Please provide only a brief summary
      of roles and responsibilities.
    </Alert>

    <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Responsibilities</label>
    <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2" placeholder="Please provide only a brief summary of roles and responsibilities..."></textarea>

    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">From Date</label>
    <div class="relative max-w-sm mb-3">
      <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
    </div>

    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">To Date</label>
    <div class="relative max-w-sm mb-3">
      <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
    </div>
  </template>
  <template #footer>
    <div class="flex justify-between">
      <button @click="closeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
        Cancel
      </button>
      <button @click="closeModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Add
      </button>
    </div>
  </template>
</Modal>
</template>
