<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { mdiEmail } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import MailingListView from '@/Components/MailingListView.vue'
const props = defineProps({
  mailingLists: Object,
  members: Object,
  mailingId: Number,
  all_emails: String,
})
</script>
<template>
  <LayoutAuthenticated title="Mailing Lists">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiEmail" title="Mailing Lists" main> &nbsp; </SectionTitleLineWithButton>
      <MailingListView :mailing-lists="mailingLists" :all-emails="all_emails" :members="members" :mailing-id="mailingId" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
