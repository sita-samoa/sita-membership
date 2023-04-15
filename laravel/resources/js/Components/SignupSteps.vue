<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';
import { Button, Input, Tabs, Tab } from 'flowbite-vue'

const activeTab = ref('first')
</script>
<template>
  <tabs v-model="activeTab" class="p-5"> <!-- class appends to content DIV for all tabs -->
    <tab name="first" title="First">

      <h4 class="py-3">Membership Type</h4>
      <div class="flex items-center mb-4">
        <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default radio</label>
      </div>
      <div class="flex items-center">
        <input checked id="default-radio-2" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Checked state</label>
      </div>

      <Link>
        <Button class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="second" title="Second">

      <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
      <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <option selected>Choose a title</option>
        <option value="US">Mr</option>
        <option value="CA">Mrs</option>
        <option value="FR">Ms</option>
        <option value="DE">Dr</option>
      </select>

      <Input placeholder="enter your first name" label="First name" />
      <Input placeholder="enter your last name" label="Last name" />
      <div>Gender</div>
      <div class="flex items-center mb-4">
        <input id="default-radio-1" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Default radio</label>
      </div>
      <div class="flex items-center">
        <input checked id="default-radio-2" type="radio" value="" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Checked state</label>
      </div>

      <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of birth</label>
      <div class="relative max-w-sm">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
        </div>
        <input datepicker type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
      </div>

      <Input placeholder="enter your job title" label="Job title" />
      <Input placeholder="enter your current employer" label="Current employer" />
    </tab>
    <tab name="third" title="Third">
      Lorem...3
    </tab>
    <tab name="fourth" title="Fourth" :disabled="true">
      Lorem...4
    </tab>
  </tabs>
</template>
