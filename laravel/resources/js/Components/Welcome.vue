<script setup>
import { usePage } from '@inertiajs/vue3'
import { mdiSendCheck, mdiClockOutline, mdiCurrencyUsd, mdiClipboardText, mdiDecagram } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue'
import CardBoxComponentTitle from '@/Components/CardBoxComponentTitle.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import SectionBanner from '@/Components/SectionBanner.vue'
import { gradientBgPinkRed } from '@/colors'

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
  totalCollected: {
    type: Number,
    default: 0,
  },
  totalEndorsed: {
    type: Number,
    default: 0,
  },
})
</script>

<template>
  <!-- welcome and sign up -->
  <div class="mb-6">
    <CardBoxComponentTitle :title="'Tālofa, ' + usePage().props.auth.user.name" />
    <SectionBanner v-if="!$page.props.user.member_id || !$page.props.user?.completion?.data?.part2?.status" :class="gradientBgPinkRed">
      <h1 class="text-3xl text-white mb-6">Click the Sign up button to begin.</h1>
      <div>
        <BaseButton route-name="members.signup" :icon="mdiClipboardText" label="Signup" target="_blank" rounded-full />
      </div>
    </SectionBanner>

    <SectionBanner v-else :class="gradientBgPinkRed">
      <h1 class="text-3xl text-white mb-6">Click the Signup details button to review your details.</h1>
      <div>
        <BaseButton route-name="members.signup" :icon="mdiClipboardText" label="Signup details" target="_blank" rounded-full />
      </div>
    </SectionBanner>
  </div>

  <!-- exec dash -->
  <div v-if="$page.props.user.permissions.canReadAny">
    <CardBoxComponentTitle title="Members" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
      <CardBoxWidget is-hoverable color="text-green-500" :icon="mdiCurrencyUsd" :number="totalCollected" prefix="$" label="Estimated Total Collected" href="/members?membership_status_id=4" />
      <CardBoxWidget is-hoverable color="text-red-500" :icon="mdiCurrencyUsd" :number="totalOwing" prefix="$" label="Estimated Total Owing" href="/members?membership_status_id=5" />
      <CardBoxWidget is-hoverable color="text-yellow-200" :icon="mdiSendCheck" :number="totalSubmitted" label="Pending Endorsements" href="/members?membership_status_id=2" />
      <CardBoxWidget is-hoverable color="text-pink-500" :icon="mdiClockOutline" :number="totalLapsed" label="Lapsed Membership" href="/members?membership_status_id=5" />
      <CardBoxWidget is-hoverable color="text-indigo-500" :icon="mdiDecagram" :number="totalEndorsed" label="Pending Acceptance" href="/members?membership_status_id=3" />
    </div>
  </div>
</template>
