<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { Alert, ListGroup, ListGroupItem, Button } from 'flowbite-vue'
import MemberSummaryCard from './MemberSummaryCard.vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue'
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue'

const props = defineProps([
    'member',
    'options',
])

const completion = props.options.completion.data

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
function accept() {
    form.put(route('members.accept', props.member.id), {
        resetOnSuccess: false,
    })
}
function sendSubReminder() {
  form.put(route('members.send-sub-reminder', props.member.id), {
    resetOnSuccess: false,
  })
}

const application_status_id = computed(() => {
    let m = props.member
    if (m.membership_application_status_id) {
        return m.membership_application_status_id
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

  <Button class="w-full mb-3" :disabled="!application_ready_for_submission" v-if="application_status_id <= 1" default @click.prevent="submit">Submit</Button>

  <Button class="w-full mb-3" v-if="application_status_id === 2" default @click.prevent="endorse">Endorse</Button>

  <Button class="w-full mb-3" v-if="application_status_id === 3" default @click.prevent="accept">Accept</Button>

  <Button class="w-full mb-3" v-if="application_status_id === 4 && $page.props.permissions.canSendSubReminder" default @click.prevent="sendSubReminder">Send Sub Reminder</Button>

</div>
</template>
