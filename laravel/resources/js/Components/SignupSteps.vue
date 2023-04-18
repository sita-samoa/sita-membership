<script setup>
import { ref, computed, watch } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Alert, Button, Progress, Input, Tabs, Tab } from 'flowbite-vue'
import MemberQualifications from '@/Components/MemberQualifications.vue';
import MemberDocuments from '@/Components/MemberDocuments.vue';
import MemberWorkExperience from '@/Components/MemberWorkExperience.vue';
import MemberReferees from '@/Components/MemberReferees.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps([
  'options',
]);

const member_id = ref(-1)

const MIN_STEP = 1
const MAX_STEP = 9

const activeTab = ref('first')
const currentStep = ref(MIN_STEP)
const disableTabs = ref(false)

const form = useForm({
  first_name: '',
  last_name: '',
  title_id: '',
  dob: '',
  membership_type_id: 1,
  gender_id: '',
  job_title: '',
  current_employer: '',
  home_address: '',
  home_phone: '',
  home_mobile: '',
  home_email: '',
  work_address: '',
  work_phone: '',
  work_mobile: '',
  work_email: '',
  other_membership: '',
  membership_status_id: 1,
  note: '',
  membership_application_status_id: '',
})
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

    // redirect to member/1
    window.location.href = '/members/' + member_id.value
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

function submit() {
  if (member_id.value > 0) {
    form.put(route('members.update', member_id.value), {
      preserveScroll: true,
      resetOnSuccess: false,
      onSuccess() {
        nextStep()
      }
    })
  }
  else {
    form.post(route('members.signup'), {
      preserveScroll: true,
      resetOnSuccess: false,
      onSuccess() {
        nextStep()
      }
    })
  }
}

watch(
  () => usePage().props.flash.member_id,
  (newValue) => {
    if (newValue > 0) {
      member_id.value = newValue
    }
  }
)

</script>

<template>
<div>
  <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

    <div class="mb-3">
      <Progress :progress="progress"></Progress>
    </div>
    <tabs v-model="activeTab" class="p-5">
      <!-- class appends to content DIV for all tabs -->
      <tab name="first" title="Membership Type" :disabled="disableTabs">
        <form @submit.prevent="submit">

          <InputLabel for="membershipType" value="Membership Type" class="mb-4" />

          <div class="mb-4">
            <div class="flex items-center" v-for="m in props.options.membership_type_options">
              <input :id="m.id" type="radio" :value="m.id" v-model="form.membership_type_id" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
              <InputLabel :for="m.id" :value="m.title" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" />
            </div>

            <InputError class="mt-2" :message="form.errors.membership_type_id" />
          </div>

          <!-- next button -->
          <Button type="submit" class="p-3 mt-3">Next</Button>
        </form>
      </tab>
      <tab name="second" title="General" :disabled="disableTabs">

        <form @submit.prevent="submit">

          <InputLabel for="title" value="Title" class="mb-4" />
          <select id="title" v-model="form.title_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-3">
            <option selected value="null">Choose a title</option>
            <option v-for="t in props.options.title_options" :value="t.id">{{ t.title }}</option>
          </select>
          <InputError class="mt-2" :message="form.errors.title_id" />

          <Input v-model="form.first_name" placeholder="enter your first name" label="First name" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.first_name" />

          <Input v-model="form.last_name" placeholder="enter your last name" label="Last name" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.last_name" />

          <InputLabel for="gender" value="Gender" class="mb-4" />
          <div class="flex items-center mb-4" v-for="g in props.options.gender_options">
            <input :id="g.id" type="radio" :value="g.id" name="default-radio" v-model="form.gender_id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <InputLabel :for="g.id" :value="g.title" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" />
          </div>
          <InputError class="mt-2" :message="form.errors.gender_id" />

          <InputLabel for="dob" value="Date of birth" class="mb-4" />
          <div class="relative max-w-sm mb-3">
            <input type="date" id="dob" v-model="form.dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date">
          </div>
          <InputError class="mt-2" :message="form.errors.dob" />

          <Input v-model="form.job_title" placeholder="enter your job title" label="Job title" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.job_title" />

          <Input v-model="form.current_employer" placeholder="enter your current employer" label="Current employer" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.current_employer" />

          <!-- next button -->
          <Button type="submit" class="p-3 mt-3">Next</Button>
        </form>
      </tab>
      <tab name="third" title="Home" :disabled="disableTabs">
        <h5 class="mb-3">Home details</h5>

        <form @submit.prevent="submit">
          <Input v-model="form.home_address" placeholder="enter your address" label="Address" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.home_address" />

          <Input v-model="form.home_phone" placeholder="enter your phone" label="Phone" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.home_phone" />

          <Input v-model="form.home_mobile" placeholder="enter your mobile" label="Mobile" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.home_mobile" />

          <Input v-model="form.home_email" placeholder="enter your email" label="Email" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.home_email" />

          <!-- next button -->
          <Button type="submit" class="p-3 mt-3">Next</Button>

        </form>
      </tab>
      <tab name="fourth" title="Work" :disabled="disableTabs">
        <h5 class="mb-3">Work details</h5>

        <form @submit.prevent="submit">
          <Input v-model="form.work_address" placeholder="enter your address" label="Address" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.work_address" />

          <Input v-model="form.work_phone" placeholder="enter your phone" label="Phone" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.work_phone" />

          <Input v-model="form.work_mobile" placeholder="enter your mobile" label="Mobile" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.work_mobile" />

          <Input v-model="form.work_email" placeholder="enter your email" label="Email" class="mb-2" />
          <InputError class="mt-2" :message="form.errors.work_email" />

          <!-- next button -->
          <Button type="submit" class="p-3 mt-3">Next</Button>

        </form>
      </tab>
      <tab name="fifth" title="Memberships" :disabled="disableTabs">

        <form @submit.prevent="submit">
          <InputLabel for="message" value="Other Memberships" class="mb-4" />
          <textarea id="message" v-model="form.other_membership" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="List each professional organisation you are a member of in a separate line..."></textarea>
          <InputError class="mt-2" :message="form.errors.other_membership" />

          <!-- next button -->
          <Button type="submit" class="p-3 mt-3">Next</Button>

        </form>
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
          <InputLabel value="Mailing Lists" class="mb-4" />
          <Alert type="info" class="mb-2 mt-3">Please check the mail lists you
            want to join.
          </Alert>

          <div class="flex items-center mb-4" v-for="m in mailingOptions">
            <input :id="m.id" type="checkbox" :value="m.id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <InputLabel :for="m.id" :value="m.name" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" />
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
</div>
</div>
</template>
