<script setup>
import { ref, computed, watch } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Alert, TheCard, ListGroup, ListGroupItem, Button, Badge, Input, Tabs, Tab } from 'flowbite-vue'
import MemberQualifications from '@/Components/MemberQualifications.vue';
import MemberDocuments from '@/Components/MemberDocuments.vue';
import MemberWorkExperience from '@/Components/MemberWorkExperience.vue';
import MemberReferees from '@/Components/MemberReferees.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue';
import AlertCircleOutlineIcon from 'vue-material-design-icons/AlertCircleOutline.vue';

const props = defineProps([
  'member',
  'options',
]);


const form = useForm({
  first_name: '',
})

const completion = ref([
  { part: 1, status: 1, title: "Membership Type" },
  { part: 2, status: 1, title: "General" },
  { part: 3, status: 0, title: "Home Address" },
  { part: 4, status: 0, title: "Work Address" },
  { part: 5, status: 1, title: "Other Memberships" },
  { part: 6, status: 1, title: "Academic Qualifications" },
  { part: 7, status: 1, title: "Work Experience" },
  { part: 8, status: 1, title: "Referees" },
  { part: 9, status: 0, title: "Mailing Lists" },
])

function submit() {
  console.log('submitted')
}

const member_name = computed(() => {
  let member = props.member;
  return member.first_name + " " + member.last_name
})

</script>

<template>
<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
  <Alert type="info" class="mb-2">
    <strong>Draft</strong><br/>
    Complete your signup and press Submit.
  </Alert>

  <the-card href="#" class="mb-3">
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
    <list-group-item v-for="c in completion">
      <template #prefix>
        <CheckCircleOutlineIcon fillColor="green" v-if="c.status" />
        <AlertCircleOutlineIcon fillColor="blue" v-else />
      </template>
      {{ c.title }}
    </list-group-item>
  </list-group>

  <Button class="w-full" default @click.prevent="submit">Submit</Button>
</div>
</template>
