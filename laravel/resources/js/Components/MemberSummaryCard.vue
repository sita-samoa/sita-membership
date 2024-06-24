<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { FwbCard, FwbBadge } from 'flowbite-vue'
import { mdiSendCheck, mdiFile, mdiCheckDecagram, mdiClockOutline, mdiDecagram, mdiClockAlertOutline, mdiAccountOff, mdiAccountCancel } from '@mdi/js'
import BaseIcon from '@/Components/BaseIcon.vue'

const props = defineProps({
  member: {
    type: Object,
    default: {
      data: [],
      links: [],
    },
  },
  linkRoute: {
    type: String,
    default: 'members.index',
  },
})

const member_name = computed(() => {
  let member = props.member
  let fullName = member.first_name + ' ' + member.last_name
  if (!fullName || fullName.includes('null')) {
    fullName = 'Unnamed Member'
  }
  return fullName
})

let badgeType = 'default'
let badgeIcon = mdiSendCheck

switch (props.member.membership_status_id) {
  case 1:
    // draft
    badgeType = 'default'
    badgeIcon = mdiFile
    break
  case 2:
    // submitted
    badgeType = 'yellow'
    badgeIcon = mdiSendCheck
    break
  case 3:
    // endorsed
    badgeType = 'indigo'
    badgeIcon = mdiDecagram
    break
  case 4:
    // accepted
    badgeType = 'purple'
    badgeIcon = mdiCheckDecagram
    break
  case 5:
    // lapsed
    badgeType = 'pink'
    badgeIcon = mdiClockOutline
    break
  case 6:
    // expired
    badgeType = 'red'
    badgeIcon = mdiClockAlertOutline
    break
  case 7:
    // banned
    badgeType = 'dark'
    badgeIcon = mdiAccountOff
    break
  case 8:
    // banned
    badgeType = 'red'
    badgeIcon = mdiAccountCancel
    break
}

const linkData = { member: props.member.id, tab: 1 }
</script>
<template>
  <fwb-card variant="horizontal" :img-src="'https://api.dicebear.com/6.x/initials/svg?backgroundColor=30cbef&scale=50&seed=' + member_name" :alt="member_name">
    <div class="p-5">
      <Link :href="route(props.linkRoute, linkData)">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
          <span v-if="props.member.title_id">{{ props.member.title.title }}</span> {{ member_name }}
        </h5>

        <div class="flex mb-3">
          <fwb-badge v-if="props.member.membership_status" :type="badgeType">
            <template #icon>
              <BaseIcon :path="badgeIcon" />
            </template>
            {{ props.member.membership_status.title }}
          </fwb-badge>
          <fwb-badge v-else type="default">Draft</fwb-badge>
          <fwb-badge type="default">{{ props.member.membership_type.title }}</fwb-badge>
        </div>

        <p v-if="props.member.job_title" class="font-normal text-gray-700 dark:text-gray-400">{{ props.member.job_title }}, {{ props.member.current_employer }}</p>
      </Link>
    </div>
  </fwb-card>
</template>
