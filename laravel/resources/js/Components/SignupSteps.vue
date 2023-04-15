<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3';
import { Alert, Button, Progress, Input, Tabs, Tab } from 'flowbite-vue'
import MemberQualifications from '@/Components/MemberQualifications.vue';
import MemberDocuments from '@/Components/MemberDocuments.vue';
import MemberWorkExperience from '@/Components/MemberWorkExperience.vue';
import MemberReferees from '@/Components/MemberReferees.vue';

const MIN_STEP = 1
const MAX_STEP = 9

const activeTab = ref('first')
const currentStep = ref(MIN_STEP)
const disableTabs = ref(false)

const membershipTypeOptions = [
  { id: 1, name: "Full" },
  { id: 2, name: "Associate" },
  { id: 3, name: "Affiliate" },
  { id: 4, name: "Student" },
  { id: 5, name: "Fellow" },
]
const titleOptions = [
  { id: 1, name: "Mr" },
  { id: 2, name: "Mrs" },
  { id: 3, name: "Ms" },
  { id: 4, name: "Dr" },
]
const genderOptions = [
  { id: 1, name: "Male" },
  { id: 2, name: "Female" },
  { id: 3, name: "Gender non-conforming" },
  { id: 4, name: "Non binary" },
  { id: 5, name: "Prefer not to say" },
]
const mailingOptions = [
  { id: 1, name: "SITA General" },
  { id: 2, name: "SITA Members" },
]

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
    <tab name="first" title="Membership Type" :disabled="disableTabs">

      <h5 class="py-3">Membership Type</h5>
      <div class="flex items-center mb-4" v-for="m in membershipTypeOptions">
        <input :id="m.id" type="radio" :value="m.id" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label :for="m.id" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ m.name }}</label>
      </div>

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="second" title="General" :disabled="disableTabs">

      <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
      <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-3">
        <option selected>Choose a title</option>
        <option v-for="t in titleOptions" :value="t.id">{{ t.name }}</option>
      </select>

      <Input placeholder="enter your first name" label="First name" class="mb-2" />
      <Input placeholder="enter your last name" label="Last name" class="mb-2" />

      <div class="my-3">Gender</div>

      <div class="flex items-center mb-4" v-for="g in genderOptions">
        <input :id="g.id" type="radio" :value="g.id" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label :for="g.id" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ g.name }}</label>
      </div>

      <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date of birth</label>
      <div class="relative max-w-sm mb-3">
        <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
      </div>

      <Input placeholder="enter your job title" label="Job title" class="mb-2" />
      <Input placeholder="enter your current employer" label="Current employer" class="mb-2" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="third" title="Home" :disabled="disableTabs">
      <h5 class="mb-3">Home details</h5>

      <Input placeholder="enter your address" label="Address" class="mb-2" />
      <Input placeholder="enter your phone" label="Phone" class="mb-2" />
      <Input placeholder="enter your mobile" label="Mobile" class="mb-2" />
      <Input placeholder="enter your email" label="Email" class="mb-2" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="fourth" title="Work" :disabled="disableTabs">
      <h5 class="mb-3">Work details</h5>

      <Input placeholder="enter your address" label="Address" class="mb-2" />
      <Input placeholder="enter your phone" label="Phone" class="mb-2" />
      <Input placeholder="enter your mobile" label="Mobile" class="mb-2" />
      <Input placeholder="enter your email" label="Email" class="mb-2" />

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="fifth" title="Memberships" :disabled="disableTabs">

      <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Other Memberships</label>
      <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="List each professional organisation you are a member of in a separate line..."></textarea>

      <!-- next button -->
      <Link href="#">
        <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </Link>
    </tab>
    <tab name="sixth" title="Qualifications" :disabled="disableTabs">
      <MemberQualifications />
      <MemberDocuments />

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
    <tab name="seventh" title="Work Experience" :disabled="disableTabs">
      <MemberWorkExperience />

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
    <tab name="eighth" title="Referees" :disabled="disableTabs">
      <MemberReferees />

      <!-- next button -->
      <div>
        <Link href="#">
          <Button @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
        </Link>
      </div>
    </tab>
    <tab name="ninth" title="Mailing Lists" :disabled="disableTabs">
      <div>
        <h5>Mailing lists</h5>
        <Alert type="info" class="mb-2 mt-3">Please check the mail lists you
          want to join.
        </Alert>

        <div class="flex items-center mb-4" v-for="m in mailingOptions">
          <input :id="m.id" type="checkbox" :value="m.id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label :for="m.id" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ m.name }}</label>
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
