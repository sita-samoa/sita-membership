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

const props = defineProps([
  'member',
  'options',
]);

const member_name = computed(() => {
  return props.member.first_name + " " + props.member.last_name
})

</script>

<template>
<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
  <Alert type="info" class="mb-2 mt-3">
    <strong>Draft</strong><br/>
    Complete your signup and press Submit.
  </Alert>

  <the-card href="#">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ member_name }}</h5>
    <p>
      <Badge type="green">Draft</Badge>
      <Badge type="green">Full</Badge>
    </p>
    <p class="font-normal text-gray-700 dark:text-gray-400">
      {{ props.member.job_title }}, {{ props.member.current_employer }}
    </p>
  </the-card>

  <list-group>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path></svg>
      </template>
      Membership Type
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path></svg>
      </template>
      General
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Home Address
    </list-group-item>
    <list-group-item>
      <template #suffix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z" clip-rule="evenodd"></path></svg>
      </template>
      Work Address
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Other Memberships
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Academic Qualifications
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Work Experience
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Referees
    </list-group-item>
    <list-group-item>
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Mailing Lists
    </list-group-item>
  </list-group>

  <Button default>Submit</Button>

  <div>
    {{ member }}
  </div>
</div>
</template>
