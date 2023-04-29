<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { Alert, ListGroup, ListGroupItem, Button } from 'flowbite-vue'
import MemberSummary from './MemberSummary.vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue'
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue'

const props = defineProps([
  'member',
  'options',
])

const completion = props.options.completion

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

const application_status_id = computed(() => {
  let m = props.member
  if (m.membership_application_status_id) {
    return m.membership_application_status_id
  }
  return 0
})

</script>

<template>
<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
  <!-- Alerts -->
  <Alert type="info" class="mb-2" v-if="application_status_id === 0">
    <strong>Draft</strong><br/>
    Complete your signup and press Submit.
  </Alert>

  <Alert type="info" class="mb-2" v-if="application_status_id === 2">
    <strong>Submitted</strong><br/>
    Your application has been submitted and is under review.
  </Alert>

  <Alert type="info" class="mb-2" v-if="application_status_id === 3">
    <strong>Endorsed</strong><br/>
    Your application has been endorsed and is awaiting settlement.
  </Alert>

  <MemberSummary :member="props.member" link-route="members.signup.index" class="mb-3" />

  <list-group class="w-full mb-3">
    <Link :href="route('members.signup.index', props.member.id)">
      <list-group-item v-for="c in completion">
        <template #prefix>
          <CheckCircleOutlineIcon fillColor="green" v-if="c.status" />
          <AlertCircleOutlineIcon fillColor="blue" v-else />
        </template>
        {{ c.title }}
      </list-group-item>
    </Link>
  </list-group>

  <!-- Action buttons -->
  <Button class="w-full mb-3" v-if="application_status_id === 0" default @click.prevent="submit">Submit</Button>

  <Button class="w-full mb-3" v-if="application_status_id === 2" default @click.prevent="endorse">Endorse</Button>

  <Button class="w-full mb-3" v-if="application_status_id === 3" default @click.prevent="accept">Accept</Button>

</div>
</template>
