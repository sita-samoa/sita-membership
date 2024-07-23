<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SignupSummary from '@/Components/SignupSummary.vue'
import { mdiBadgeAccountHorizontalOutline } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'

defineProps(['member', 'options', 'data', 'statuses', 'membershipStatuses', 'invoices'])
</script>

<template>
  <LayoutAuthenticated title="Summary">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiBadgeAccountHorizontalOutline" title="Summary" main> &nbsp; </SectionTitleLineWithButton>
      <SignupSummary :member="member" :options="options" :data="data" :statuses="statuses" :membershipStatuses="membershipStatuses" :invoices="invoices" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
