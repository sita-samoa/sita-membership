<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { Alert, Button } from 'flowbite-vue'
import { mdiSendCheck, mdiClockOutline, mdiCurrencyUsd, mdiReload } from '@mdi/js'
import CardBox from '@/Components/CardBox.vue'
import BaseButton from '@/Components/BaseButton.vue'
import CardBoxComponentTitle from '@/Components/CardBoxComponentTitle.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'

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
  <!-- welcome and sign up -->
  <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
    <CardBox is-hoverable>
      <CardBoxComponentTitle :title="'Tālofa ' + usePage().props.auth.user.name">
        <BaseButton :icon="mdiReload" color="whiteDark" rounded-full />
      </CardBoxComponentTitle>
      <div class="space-y-3">
        <!-- does have a profile -->
        <div v-if="!$page.props.user.member_id || !$page.props.user?.completion?.data?.part2?.status">
          <Alert type="info" class="mb-2 mt-3"> Click the Sign up button to begin. </Alert>

          <Link href="/members/signup">
            <Button class="p-3 mt-3">Sign Up</Button>
          </Link>
        </div>

        <!-- has a profile -->
        <div v-else>
          <Alert type="info" class="mb-2 mt-3"> Click the View details button to review your details. </Alert>

          <Link :href="route('members.show', { id: $page.props.user.member_id })">
            <Button class="p-3 mt-3">View details</Button>
          </Link>
        </div>
      </div>
    </CardBox>
  </div>

  <!-- exec dash -->
  <div v-if="$page.props.user.permissions.canReadAny">
    <CardBoxComponentTitle title="Executive Dashboard" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
      <CardBoxWidget is-hoverable color="text-emerald-500" :icon="mdiSendCheck" :number="totalSubmitted" label="Pending Endorsements" href="/members?membership_status_id=2" />
      <CardBoxWidget is-hoverable color="text-blue-500" :icon="mdiClockOutline" :number="totalLapsed" label="Lapsed Membership" href="/members?membership_status_id=5" />
      <CardBoxWidget is-hoverable color="text-red-500" :icon="mdiCurrencyUsd" :number="totalOwing" prefix="$" label="Estimated Total Owing" href="/members?membership_status_id=5" />
    </div>
  </div>
</template>
