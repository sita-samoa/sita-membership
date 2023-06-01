<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import SignupSteps from '../../Components/SignupSteps.vue'

defineProps(['member', 'options', 'qualifications', 'permissions', 'referees', 'memberWorkExperiences', 'supportingDocuments', 'memberMailingLists', 'countryList', 'tab'])
</script>

<template>
  <AppLayout title="Sign Up">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Sign Up</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <SignupSteps :member="member" :options="options" :qualifications="qualifications" :permissions="permissions" :referees="referees" :member-work-experiences="memberWorkExperiences" :supporting-documents="supportingDocuments" :member-mailing-lists="memberMailingLists" :country-list="countryList" :tab="tab" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
