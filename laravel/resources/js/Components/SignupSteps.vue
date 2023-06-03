<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import { Button, Progress, Input, Tabs, Tab } from 'flowbite-vue'
import MemberQualifications from '@/Components/MemberQualifications.vue'
import MemberDocuments from '@/Components/MemberDocuments.vue'
import MemberWorkExperience from '@/Components/MemberWorkExperience.vue'
import MemberMailingListPreference from '@/Components/MemberMailingListPreference.vue'
import MemberReferees from '@/Components/MemberReferees.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'

const props = defineProps({
  options: Object,
  member: {
    type: Object,
    default: {
      id: 0,
    },
  },
  completion: Object,
  qualifications: Object,
  permissions: Object,
  referees: Object,
  memberWorkExperiences: Object,
  supportingDocuments: Object,
  memberMailingLists: Object,
  countryList: Object,
  tab: String,
})

const OTHER_MEMBERSHIPS = 'viewed_other_memberships'
const MAILING_LIST = 'viewed_mailing_list'

const page = usePage()

const member_id = ref(props.member.id)

const applicationSubmitted = props.member.membership_status_id != '' && props.member.membership_status_id > 1

const MIN_STEP = 0
const MAX_STEP = 9

const currentStep = ref(MIN_STEP)
const activeTab = ref(getActiveTab())
const disableTabs = ref(true)

const setDefault = !(page.props.user.permissions.canAccept || page.props.auth.user.id === 1)

function getFirstName() {
  if (setDefault) {
    return page.props.auth.user.name.split(' ')[0]
  }
  return ''
}
function getLastName() {
  let parts = page.props.auth.user.name.split(' ')
  if (setDefault && parts.length > 1) {
    return parts[parts.length - 1]
  }
  return ''
}
function getEmail() {
  if (setDefault) {
    return page.props.auth.user.email
  }
  return ''
}

const form = useForm({
  first_name: props.member.first_name ?? getFirstName(),
  last_name: props.member.last_name ?? getLastName(),
  title_id: props.member.title_id ?? -1,
  dob: props.member.dob ?? '',
  membership_type_id: props.member.membership_type_id ?? 1,
  gender_id: props.member.gender_id ?? '',
  job_title: props.member.job_title ?? '',
  current_employer: props.member.current_employer ?? '',
  home_address: props.member.home_address ?? '',
  home_phone: props.member.home_phone ?? '',
  home_mobile: props.member.home_mobile ?? '',
  home_email: props.member.home_email ?? getEmail(),
  work_address: props.member.work_address ?? '',
  work_phone: props.member.work_phone ?? '',
  work_mobile: props.member.work_mobile ?? '',
  work_email: props.member.work_email ?? getEmail(),
  other_membership: props.member.other_membership ?? '',
  membership_status_id: props.member.membership_status_id ?? 1,
  note: props.member.note ?? '',
  membership_status_id: props.member.membership_status_id ?? '',
  viewed_other_memberships: props.member.viewed_other_memberships ?? false,
  viewed_mailing_list: props.member.viewed_mailing_list ?? false,
})

const progress = computed(() => {
    if(applicationSubmitted || !props.completion) return MIN_STEP/MAX_STEP * 100
    let progress = MIN_STEP
    const parts = [1,2,3,4,5,6,7,8,9]
    const completion = props.completion?.data
    parts.map(part => {
        if(completion[`part${part}`]?.status){
            progress++
        }
    })
    let percent = progress / MAX_STEP * 100
    return percent
})

function getActiveTab() {
  switch (currentStep.value) {
    case 1:
      return 'first'
    case 2:
      return 'second'
    case 3:
      return 'third'
    case 4:
      return 'fourth'
    case 5:
      return 'fifth'
    case 6:
      return 'sixth'
    case 7:
      return 'seventh'
    case 8:
      return 'eighth'
    case 9:
      return 'ninth'
    default:
      return 'first'
  }
}

function nextStep() {
  let step = 1
  let tab = "first"
  switch (activeTab.value) {
    case 'first':
      step = 2
      tab = 'second'
      break
    case 'second':
      step = 3
      tab = 'third'
      break
    case 'third':
      step = 4
      tab = 'fourth'
      break
    case 'fourth':
      step = 5
      tab = 'fifth'
      break
    case 'fifth':
      step = 6
      tab = 'sixth'
      break
    case 'sixth':
      step = 7
      tab = 'seventh'
      break
    case 'seventh':
      step = 8
      tab = 'eighth'
      break
    case 'eighth':
      step = 9
      tab = 'ninth'
      break
    case 'ninth':
      step = 9
      tab = 'ninth'
      router.replace(route('members.show', member_id.value))
      break
  }

  currentStep.value = step
  activeTab.value = tab
}

function markFlagAsViewed(flag){
    if(flag == MAILING_LIST || flag == OTHER_MEMBERSHIPS){
    // if user has hasn't viewed any of the flags yet, then mark them as read
    if(!props.member[flag] || props.member[flag] == 0){
        form.put(route('members.view-flag', { member: member_id.value, flag_name: flag}), {
            preserveScroll: true,
            resetOnSuccess: false,
            onSuccess(){
                if(flag == MAILING_LIST){
                    nextStep()
                }
            }
        })
     }else if(flag == MAILING_LIST){
        nextStep()
     }
    }
}

function submit(flag) {
  if (member_id.value > 0) {
    if(flag){
        markFlagAsViewed(flag)
    }
    // Always check the 'General' tab
    if (form.isDirty || activeTab.value === 'second') {
      const tab = activeTab.value
      form.put(route('members.update', member_id.value), {
        preserveScroll: true,
        resetOnSuccess: false,
        onSuccess() {
          // Allow switching tabs
          disableTabs.value = false
          nextStep()
        },
        onError() {
          // Display the error tab.
          activeTab.value = tab
        },
      })
      return
    }
    if(activeTab.value !== 'ninth'){
        nextStep()
    }
  } else {
    form.post(route('members.signup.store'), {
      preserveScroll: true,
      resetOnSuccess: false,
      onSuccess(res) {
        member_id.value = res.props.flash.data.id
        nextStep()
      },
    })
  }
}

function getPartStatus(part){
    return page.props.user?.completion?.data?.[part]?.status
}

onMounted(() => {
  if (member_id.value !== 0 && !props.tab && getPartStatus('part2')) {
    // check if user have already started their membership application, have completed first 2 steps then go to summary
    router.replace(route('members.show', member_id.value))
  } else if (props.tab) {
    // check if user selected a tab from summary
    currentStep.value = Number(props.tab)
    activeTab.value = getActiveTab()
    disableTabs.value = ! page.props.user?.completion?.data?.part2?.status
  }
})
</script>

<template>
  <div>
    <div class="p-6 bg-white border-b border-gray-200 lg:p-8">
      <div class="my-3 text-sm" v-if="form.isDirty && member_id">There are unsaved changes. Press the "Next" button to save them.</div>
      <div class="mb-3">
        <Progress v-if="!applicationSubmitted" :progress="progress" />
      </div>
      <tabs v-model="activeTab" class="p-5">
        <!-- class appends to content DIV for all tabs -->
        <tab name="first" title="Membership Type" :disabled="disableTabs">
          <form @submit.prevent="submit">
            <InputLabel for="membershipType" value="Membership Type" class="mb-4" />

            <div class="mb-4">
              <div class="flex items-center" v-for="m in props.options.membership_type_options">
                <input :id="m.id" type="radio" :value="m.id" v-model="form.membership_type_id" name="default-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                <InputLabel :for="m.id" :value="m.title" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" />
              </div>

              <InputError class="mt-2" :message="form.errors.membership_type_id" />
            </div>

            <!-- next button -->
            <Button v-show="$page.props.user.permissions.canUpdate" type="submit" class="p-3 mt-3">Next</Button>
          </form>
        </tab>
        <tab name="second" title="General" :disabled="disableTabs">
          <form @submit.prevent="submit">
            <InputLabel for="title" value="Title" class="mb-4" />
            <select id="title" v-model="form.title_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-3">
              <option selected value="-1">Choose a title</option>
              <option v-for="t in props.options.title_options" :value="t.id">{{ t.title }}</option>
            </select>
            <InputError class="mt-2" :message="form.errors.title_id" />

            <Input v-model="form.first_name" placeholder="enter your first name" label="First name" class="mb-2" />
            <InputError class="mt-2" :message="form.errors.first_name" />

            <Input v-model="form.last_name" placeholder="enter your last name" label="Last name" class="mb-2" />
            <InputError class="mt-2" :message="form.errors.last_name" />

            <InputLabel for="gender" value="Gender" class="mb-4" />
            <div class="flex items-center mb-4" v-for="g in props.options.gender_options">
              <input :id="g.id" type="radio" :value="g.id" name="default-radio" v-model="form.gender_id" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
              <InputLabel :for="g.id" :value="g.title" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300" />
            </div>
            <InputError class="mt-2" :message="form.errors.gender_id" />

            <InputLabel for="dob" value="Date of birth" class="mb-4" />
            <div class="relative max-w-sm mb-3">
              <input type="date" id="dob" v-model="form.dob" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Select date" />
            </div>
            <InputError class="mt-2" :message="form.errors.dob" />

            <Input v-model="form.job_title" placeholder="enter your job title" label="Job title" class="mb-2" />
            <InputError class="mt-2" :message="form.errors.job_title" />

            <Input v-model="form.current_employer" placeholder="enter your current employer" label="Current employer" class="mb-2" />
            <InputError class="mt-2" :message="form.errors.current_employer" />

            <!-- next button -->
            <Button v-show="$page.props.user.permissions.canUpdate" type="submit" class="p-3 mt-3">Next</Button>
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

            <Input name="home_email" v-model="form.home_email" placeholder="enter your email" label="Email" class="mb-2" />
            <InputError class="mt-2" :message="form.errors.home_email" />

            <!-- next button -->
            <Button v-show="$page.props.user.permissions.canUpdate" type="submit" class="p-3 mt-3">Next</Button>
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

            <Input name="work_email" v-model="form.work_email" placeholder="enter your email" label="Email" class="mb-2" />
            <InputError class="mt-2" :message="form.errors.work_email" />

            <!-- next button -->
            <Button v-show="$page.props.user.permissions.canUpdate" type="submit" class="p-3 mt-3">Next</Button>
          </form>
        </tab>
      <tab name="fifth" title="Memberships" :disabled="disableTabs">
        <form @submit.prevent="submit('viewed_other_memberships')">
            <InputLabel for="message" value="Other Memberships" class="mb-4" />
            <textarea id="message" v-model="form.other_membership" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="List each professional organisation you are a member of in a separate line..."></textarea>
            <InputError class="mt-2" :message="form.errors.other_membership" />
            <!-- next button -->
            <Button v-show="$page.props.user.permissions.canUpdate"  type="submit" class="p-3 mt-3">Next</Button>
        </form>
      </tab>
      <tab name="sixth" title="Qualifications" :disabled="disableTabs">
        <MemberQualifications :member_id="member_id" :list="props.qualifications" :countryList="props.countryList" :editable="$page.props.user.permissions.canUpdate" />
        <MemberDocuments :member_id="member_id" :list="props.supportingDocuments" />

        <!-- next button -->
        <Button v-show="$page.props.user.permissions.canUpdate" @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </tab>
      <tab name="seventh" title="Work Experience" :disabled="disableTabs">
        <MemberWorkExperience :member-id="member.id" :member-work-experiences="memberWorkExperiences" />

        <!-- next button -->
        <Button v-show="$page.props.user.permissions.canUpdate" @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </tab>
      <tab name="eighth" title="Referees" :disabled="disableTabs">
        <MemberReferees :member_id="member_id" :list="props.referees" />

        <!-- next button -->
        <Button v-show="$page.props.user.permissions.canUpdate" @click.prevent="nextStep" class="p-3 mt-3">Next</Button>
      </tab>
      <tab name="ninth" title="Mailing Lists" :disabled="disableTabs">
        <MemberMailingListPreference @submit="submit" :member_id="member_id" :list="props.memberMailingLists" :mailing_options="props.options.mailing_options" />
      </tab>
      <div v-show="props.tab || $page.props.user?.completion?.data?.part2?.status" class="w-full flex justify-end">
          <Link class="underline text-indigo-500 text-sm" :href="route('members.show', member_id)">View Application Summary</Link>
      </div>
    </tabs>
</div>
</div>
</template>
