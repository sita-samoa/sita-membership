<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { Alert, ListGroup, ListGroupItem, Button } from 'flowbite-vue'
import MemberSummaryCard from '@/Components/MemberSummaryCard.vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue'
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue'
import AcceptModal from '@/Components/AcceptModal.vue'
import RejectionModal from '@/Components/RejectionModal.vue'
import CardBox from '@/Components/CardBox.vue'

const props = defineProps(['member', 'options', 'data'])

const completion = props.options.completion.data
const showAcceptanceModal = ref(false)
const showActivateModal = ref(false)
const showRejectionModal = ref(false)

const form = useForm({})

function submit() {
  form.put(route('members.submit', props.member.id), {
    resetOnSuccess: false,
  })
}
function endorse() {
  form.put(route('members.endorse', props.member.id), {
    resetOnSuccess: false,
  })
}
function sendSubReminder() {
  form.put(route('members.send-sub-reminder', props.member.id), {
    resetOnSuccess: false,
  })
}
function sendPastDueSubReminder() {
  form.put(route('members.send-past-due-sub-reminder', props.member.id), {
    resetOnSuccess: false,
  })
}

const application_status_id = computed(() => {
  let m = props.member
  if (m.membership_status_id) {
    return m.membership_status_id
  }
  return 0
})

const application_ready_for_submission = props.options.completion.overall.status
</script>

<template>
  <CardBox>
    <!-- Alerts -->
    <Alert v-if="application_status_id === 0" type="info" class="mb-6">
      <strong>Draft</strong><br />
      Complete your signup and press Submit.
    </Alert>

    <Alert v-if="application_status_id === 2" type="info" class="mb-6">
      <strong>Submitted</strong><br />
      Your application has been submitted and is under review.
    </Alert>

    <Alert v-if="application_status_id === 3" type="info" class="mb-6">
      <strong>Endorsed</strong><br />
      Your application has been endorsed and is awaiting settlement.
    </Alert>

    <Alert v-if="application_status_id === 8 || props.data.reason != null" type="danger" class="mb-6">
      <strong>Rejected</strong><br />
      Your application was rejected. See below for more information:
      <ul v-if="props.data.reason != null" class="list-group">
        <li class="list-item mt-2">{{ props.data.reason }}</li>
      </ul>
    </Alert>

    <MemberSummaryCard :member="props.member" link-route="members.signup.index" class="mb-6" />

    <list-group class="w-full">
      <Link v-for="(c, index) in completion" :href="route('members.signup.index', { member: props.member.id, tab: index.replace('part', '') })">
        <list-group-item>
          <template #prefix>
            <CheckCircleOutlineIcon v-if="c.status" fill-color="green" />
            <AlertCircleOutlineIcon v-else fill-color="blue" />
          </template>
          {{ c.title }}
        </list-group-item>
      </Link>
    </list-group>

    <template #footer>
      <p v-show="!application_ready_for_submission" class="w-full mb-6 ml-2 text-sm text-gray-500">Please ensure all sections are completed before submitting</p>

      <!-- Action buttons -->
      <Button v-if="application_status_id <= 1" class="w-full mb-6" :disabled="!(application_ready_for_submission || $page.props.user.permissions.canSubmit) || form.processing" default @click.prevent="submit">Submit</Button>
      <Button v-if="application_status_id === 2 && $page.props.user.permissions.canEndorse" class="w-full mb-3" :disabled="form.processing" default @click.prevent="endorse">Endorse</Button>
      <Button v-if="application_status_id === 2 && $page.props.user.permissions.canReject" class="w-full mb-6 bg-red-500 hover:bg-red-400" :disabled="form.processing" default @click.prevent="showRejectionModal = true">Reject</Button>
      <Button v-if="application_status_id === 3 && $page.props.user.permissions.canAccept" class="w-full mb-6" :disabled="form.processing" default @click.prevent="showAcceptanceModal = true">Accept</Button>
      <Button v-if="(application_status_id === 5 || application_status_id === 6) && $page.props.user.permissions.canAccept" class="w-full mb-6" color="green" :disabled="form.processing" default @click.prevent="showActivateModal = true">Activate Membership</Button>
      <Button v-if="application_status_id === 4 && $page.props.user.permissions.canSendSubReminder" class="w-full mb-6" :disabled="form.processing" default @click.prevent="sendSubReminder">Send sub reminder</Button>
      <Button v-if="application_status_id === 5 && $page.props.user.permissions.canSendPastDueSubReminder" class="w-full mb-6" :disabled="form.processing" default @click.prevent="sendPastDueSubReminder">Send past due sub reminder</Button>

      <!-- Audit log link -->
      <Link :href="route('members.audit.index', { member: member.id })" class="underline text-indigo-500 text-sm mt-5">View audit log</Link>

      <!-- Dialog Modals -->
      <AcceptModal :show="showAcceptanceModal" :member-id="member.id" @close="showAcceptanceModal = false" />
      <AcceptModal :show="showActivateModal" :member-id="member.id" heading-text="Activate Membership" button-text="Activate" @close="showActivateModal = false" />
      <RejectionModal :show="showRejectionModal" :member-id="member.id" @close="showRejectionModal = false" />
    </template>
  </CardBox>
</template>
