<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { mdiEmail } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import MailingListView from '@/Components/MailingListView.vue'
defineProps({
  mailingLists: Object,
  membershipTypes: Object,
  members: Object,
  membershipTypeId: Number,
  membershipStatusId: Number,
  mailingId: Number,
  allEmails: String,
  subData: Object,
})
</script>
<template>
  <LayoutAuthenticated title="Mailing Lists">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiEmail" title="Mailing Lists" main> &nbsp; </SectionTitleLineWithButton>
      <MailingListView :sub-data="subData" :member-types="membershipTypes" :mailing-lists="mailingLists" :all-emails="allEmails" :members="members" :mailing-id="mailingId" :membership-type-id="membershipTypeId" :membership-status-id="membershipStatusId" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
