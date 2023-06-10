<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import SignupSummary from '@/Components/SignupSummary.vue'
import {
  mdiChartTimelineVariant,
} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

defineProps(['member', 'options', 'auditLog'])
</script>

<template>
  <LayoutAuthenticated title="Summary">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiChartTimelineVariant" title="Summary" main> &nbsp;
      </SectionTitleLineWithButton>
      <SignupSummary :member="member" :options="options" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
