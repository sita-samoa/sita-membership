<script setup>
import { ref, computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { Alert, ListGroup, ListGroupItem, Button } from 'flowbite-vue'
import MemberSummaryCard from '@/Components/MemberSummaryCard.vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue'
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue'
import AcceptModal from '@/Components/AcceptModal.vue'

const props = defineProps([
    'member',
    'options',
])

const completion = props.options.completion.data
const showAcceptanceModal = ref(false)
const showActivateModal = ref(false)

const form = useForm({
})

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
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <!-- Alerts -->
        <Alert type="info" class="mb-2" v-if="application_status_id === 0">
            <strong>Draft</strong><br />
            Complete your signup and press Submit.
        </Alert>

        <Alert type="info" class="mb-2" v-if="application_status_id === 2">
            <strong>Submitted</strong><br />
            Your application has been submitted and is under review.
        </Alert>

        <Alert type="info" class="mb-2" v-if="application_status_id === 3">
            <strong>Endorsed</strong><br />
            Your application has been endorsed and is awaiting settlement.
        </Alert>

  <MemberSummaryCard :member="props.member" link-route="members.signup.index" class="mb-3" />

  <list-group class="w-full mb-3">
    <Link v-for="(c,index) in completion" :href="route('members.signup.index', { member: props.member.id, tab: index.replace('part','')})">
      <list-group-item>
        <template #prefix>
          <CheckCircleOutlineIcon fillColor="green" v-if="c.status" />
          <AlertCircleOutlineIcon fillColor="blue" v-else />
        </template>
        {{ c.title }}
      </list-group-item>
    </Link>
  </list-group>

  <!-- Action buttons -->
  <p class="w-full my-3 ml-2 text-sm text-gray-500" v-show="!application_ready_for_submission">Please ensure all sections are completed before submitting</p>

  <Button class="w-full mb-3" :disabled="!(application_ready_for_submission || $page.props.user.permissions.canSubmit) || form.processing" v-if="application_status_id <= 1" default @click.prevent="submit">Submit</Button>

  <Button class="w-full mb-3" :disabled="form.processing" v-if="application_status_id === 2 && $page.props.user.permissions.canEndorse" default @click.prevent="endorse">Endorse</Button>

  <Button class="w-full mb-3" :disabled="form.processing" v-if="application_status_id === 3 && $page.props.user.permissions.canAccept" default @click.prevent="showAcceptanceModal = true">Accept</Button>

  <Button class="w-full mb-3" color="green" :disabled="form.processing" v-if="(application_status_id === 5 || application_status_id === 6) && $page.props.user.permissions.canAccept" default @click.prevent="showActivateModal = true">Activate Membership</Button>

  <Button class="w-full mb-3" :disabled="form.processing" v-if="application_status_id === 4 && $page.props.user.permissions.canSendSubReminder" default @click.prevent="sendSubReminder">Send sub reminder</Button>

  <Button class="w-full mb-3" :disabled="form.processing" v-if="application_status_id === 5 && $page.props.user.permissions.canSendPastDueSubReminder" default @click.prevent="sendPastDueSubReminder">Send past due sub reminder</Button>

  <!-- Audit log link -->
  <Link :href="route('members.audit.index', {member: member.id})" class="underline text-indigo-500 text-sm mt-5">View audit log</Link>

  <!-- Dialog Modals -->
  <AcceptModal :show="showAcceptanceModal" :memberId="member.id" @close="showAcceptanceModal = false" />
  <AcceptModal :show="showActivateModal" :memberId="member.id" @close="showActivateModal = false" headingText="Activate Membership" buttonText="Activate" />
</div>
</template>
