<script setup>
import { computed } from 'vue'
import { useForm, Link } from '@inertiajs/vue3';
import { Alert, TheCard, ListGroup, ListGroupItem, Button, Badge, } from 'flowbite-vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue';
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue';

const props = defineProps([
  'member',
  'options',
]);

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

const member_name = computed(() => {
  let member = props.member;
  return member.first_name + " " + member.last_name
})

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

  <the-card :ref="route('members.signup.index', props.member.id)" class="mb-3">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
      <span v-if="props.member.title_id">{{ props.member.title.title }}</span> {{ member_name }}
    </h5>

    <div class="flex mb-3">
      <Badge type="green" v-if="props.member.membership_application_status">
        {{ props.member.membership_application_status.title }}</Badge>
      <Badge type="green" v-else>Draft</Badge>
      <Badge type="green">{{ props.member.membership_type.title}}</Badge>
    </div>

    <p class="font-normal text-gray-700 dark:text-gray-400">
      {{ props.member.job_title }}, {{ props.member.current_employer }}
    </p>
  </the-card>

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
