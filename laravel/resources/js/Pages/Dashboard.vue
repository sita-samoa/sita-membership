<script setup>
import { Head } from '@inertiajs/vue3'
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import Welcome from '@/Components/Welcome.vue'

import {
  mdiChartTimelineVariant,
} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

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
})
</script>

<template>
  <LayoutAuthenticated title="Dashboard">
    <Head title="Dashboard" />
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiChartTimelineVariant" title="Dashboard" main>
      </SectionTitleLineWithButton>
      <Welcome :total-submitted="totalSubmitted" :total-lapsed="totalLapsed" :total-owing="totalOwing" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
