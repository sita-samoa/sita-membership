<script setup>
import { Link } from '@inertiajs/vue3';
import { Alert, Button, Modal, Input } from 'flowbite-vue'
import { ref } from 'vue'

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
  <h5>Referees</h5>
  <Alert type="info" class="mb-2 mt-3">Please provide details for someone
    how can verify roles and responsibilities.
  </Alert>

  <Link href="#">
    <Button class="p-3 mt-3" color="alternative" @click.prevent="showModal" >Add Referee</Button>
  </Link>
</div>

<!-- Modal -->
<Modal :size="size" v-if="isShowModal" @close="closeModal">
  <template #header>
    <div class="flex items-center text-lg">
      Add Referee
    </div>
  </template>
  <template #body>
    <Input placeholder="enter your referees name" label="Name" class="mb-2" />
    <Input placeholder="enter your referees organisation" label="Organisation" class="mb-2" />
    <Input placeholder="enter your referees phone" label="Phone" class="mb-2" />
    <Input placeholder="enter your referees email" label="email" class="mb-2" />
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
