<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SignupSteps from '@/Components/SignupSteps.vue'
import { mdiClipboardText } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'

defineProps(['member', 'completion', 'options', 'qualifications', 'permissions', 'referees', 'memberWorkExperiences', 'supportingDocuments', 'memberMailingLists', 'countryList', 'tab'])
</script>

<template>
  <LayoutAuthenticated title="Sign Up">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiClipboardText" title="Sign up" main> &nbsp; </SectionTitleLineWithButton>
      <SignupSteps :member="member" :completion="completion" :options="options" :qualifications="qualifications" :permissions="permissions" :referees="referees" :member-work-experiences="memberWorkExperiences" :supporting-documents="supportingDocuments" :member-mailing-lists="memberMailingLists" :country-list="countryList" :tab="tab" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
