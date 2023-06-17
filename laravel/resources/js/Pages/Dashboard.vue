<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import Welcome from '@/Components/Welcome.vue'
import MailingListDisplay from '@/Components/MailingListDisplay.vue'
import { mdiMonitor } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'

defineProps({
  totalSubmitted: {
    type: Number,
    default: 0,
  },
  totalLapsed: {
    type: Number,
    default: 0,
  },
  totalOwing: {
    type: Number,
    default: 0,
  },
  mailingLists: {
    type: Object,
    default: []
  }
})
</script>

<template>
  <LayoutAuthenticated title="Dashboard">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiMonitor" title="Dashboard" main> &nbsp; </SectionTitleLineWithButton>
      <Welcome :total-submitted="totalSubmitted" :total-lapsed="totalLapsed" :total-owing="totalOwing" />
      <MailingListDisplay :mailingLists="mailingLists"/>
    </SectionMain>
  </LayoutAuthenticated>
</template>
