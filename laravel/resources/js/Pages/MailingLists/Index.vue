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
})

</script>
<template>
  <LayoutAuthenticated title="Mailing Lists">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiEmail" title="Mailing Lists" main> &nbsp; </SectionTitleLineWithButton>
      <MailingListView :mailingLists="mailingLists" :members="members" :mailingId="mailingId"  />
    </SectionMain>
  </LayoutAuthenticated>
</template>