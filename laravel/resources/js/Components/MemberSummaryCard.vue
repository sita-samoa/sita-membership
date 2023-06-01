<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { TheCard, Badge } from 'flowbite-vue'

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
    default: 'members.show',
  },
})

const member_name = computed(() => {
  let member = props.member
  let fullName = member.first_name + " " + member.last_name
  if(!fullName || fullName.includes('null')){
    fullName = 'Unnamed Member'
  }
  return fullName
})

let badgeType = 'default'
switch (props.member.membership_status_id) {
  case 1:
    // draft
    badgeType = 'yellow'
    break
  case 2:
    // submitted
    badgeType = 'pink'
    break
  case 3:
    // endorsed
    badgeType = 'dark'
    break
  case 4:
    // accepted
    badgeType = 'green'
    break
}

const linkData = { member: props.member.id, tab: 1 }
</script>
<template>
  <the-card class="sm:w-full">
    <Link :href="route(props.linkRoute, linkData)">
      <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
        <span v-if="props.member.title_id">{{ props.member.title.title }}</span> {{ member_name }}
      </h5>

      <div class="flex mb-3">
        <Badge v-if="props.member.membership_status" :type="badgeType"> {{ props.member.membership_status.title }}</Badge>
        <Badge v-else type="yellow">Draft</Badge>
        <Badge type="default">{{ props.member.membership_type.title }}</Badge>
      </div>

      <p v-if="props.member.job_title" class="font-normal text-gray-700 dark:text-gray-400">
        {{ props.member.job_title }}, {{ props.member.current_employer }}
      </p>
    </Link>
  </the-card>
</template>
