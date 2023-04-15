<script setup>
import { Link } from '@inertiajs/vue3';
import { Alert, Button, Modal, Input } from 'flowbite-vue'
import { ref } from 'vue'

const isShowModal = ref(true)

function closeModal() {
  isShowModal.value = false
}
function showModal() {
  isShowModal.value = true
}
</script>
<template>
<div class="mt-3">
  <Alert type="info" class="mb-2">Please attach certified copies of
    Supporting documents.
  </Alert>
  <h5>Supporting Documents</h5>

  <Link href="#">
    <Button class="p-3 mt-3" color="alternative" @click.prevent="showModal">Add Document</Button>
  </Link>
</div>
<!-- Modal -->
<Modal :size="size" v-if="isShowModal" @close="closeModal">
  <template #header>
    <div class="flex items-center text-lg">
      Add Document
    </div>
  </template>
  <template #body>
    <Input placeholder="enter your title" label="Title" class="mb-2" />

    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">

  </template>
  <template #footer>
    <div class="flex justify-between">
      <button @click="closeModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
        Cancel
      </button>
      <button @click="closeModal" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Attach
      </button>
    </div>
  </template>
</Modal>
</template>
