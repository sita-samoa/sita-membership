<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3';
import { Alert, Button, Progress, Input, Tabs, Tab } from 'flowbite-vue'

const MIN_STEP = 1
const MAX_STEP = 9

const activeTab = ref('first')
const currentStep = ref(MIN_STEP)

const progress = computed(() => {
  let percent = currentStep.value / MAX_STEP * 100
  return percent
})

function nextStep() {
  currentStep.value = currentStep.value + 1

  if (currentStep.value > MAX_STEP) {
    currentStep.value = MAX_STEP
  }

  switch (currentStep.value) {
    case 1:
      activeTab.value = "first"
      break
    case 2:
      activeTab.value = "second"
      break
    case 3:
      activeTab.value = "third"
      break
    case 4:
      activeTab.value = "fourth"
      break
    case 5:
      activeTab.value = "fifth"
      break
    case 6:
      activeTab.value = "sixth"
      break
    case 7:
      activeTab.value = "seventh"
      break
    case 8:
      activeTab.value = "eighth"
      break
    case 9:
      activeTab.value = "ninth"
      break
  }
}

</script>
<template>
  <div class="mb-3">
    <Progress :progress="progress"></Progress>
  </div>
  <tabs v-model="activeTab" class="p-5">
    <!-- class appends to content DIV for all tabs -->
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

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
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
        <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
      </div>

      <Input placeholder="enter your job title" label="Job title" />
      <Input placeholder="enter your current employer" label="Current employer" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="third" title="Third">
      <h5>Home details</h5>

      <Input placeholder="enter your address" label="Address" />
      <Input placeholder="enter your phone" label="Phone" />
      <Input placeholder="enter your mobile" label="Mobile" />
      <Input placeholder="enter your email" label="Email" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="fourth" title="Fourth" :disabled="true">
      <h5>Work details</h5>

      <Input placeholder="enter your address" label="Address" />
      <Input placeholder="enter your phone" label="Phone" />
      <Input placeholder="enter your mobile" label="Mobile" />
      <Input placeholder="enter your email" label="Email" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="fifth" title="Fifth">
      <h5>Other Memberships</h5>

      <Input placeholder="enter your memberships" label="Memberships" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="sixth" title="Sixth">
      <div>
        <h5>Academic Qualifications</h5>

        <Link href="#">
          <Button class="p-3 mt-3">Add Qualification</Button>
        </Link>
      </div>

      <div class="mt-3">
        <Alert type="info" class="mb-2">Please attach certified copies of
          Supporting documents.
        </Alert>
        <h5>Supporting Documents</h5>

        <Link href="#">
          <Button class="p-3 mt-3">Add Document</Button>
        </Link>
      </div>

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
    <tab name="seventh" title="Seventh">
      <div>
        <h5>Work Experience</h5>

        <Link href="#">
          <Button class="p-3 mt-3">Add Work Experience</Button>
        </Link>
      </div>

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
    <tab name="eighth" title="Eighth">
      <div>
        <h5>Referees</h5>
        <Alert type="info" class="mb-2 mt-3">Please provide details for someone
          how can verify roles and responsibilities.
        </Alert>

        <Link href="#">
          <Button class="p-3 mt-3">Add Referee</Button>
        </Link>
      </div>

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
    <tab name="ninth" title="Ninth">
      <div>
        <h5>Mailing lists</h5>
        <Alert type="info" class="mb-2 mt-3">Please check the mail lists you
          want to join.
        </Alert>

        <div class="flex items-center mb-4">
          <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="default-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">SITA General</label>
        </div>
        <div class="flex items-center">
          <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="checked-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">SITA Members</label>
        </div>

      </div>

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
  </tabs>
</template>
