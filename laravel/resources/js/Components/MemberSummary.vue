<script setup>
import { computed } from 'vue'
import { TheCard, Badge, } from 'flowbite-vue'

const props = defineProps({
  member: {
    type: Object
  }
})

const member_name = computed(() => {
  let member = props.member
  return member.first_name + " " + member.last_name
})

</script>
<template>
<the-card :ref="route('members.signup.index', props.member.id)" class="mb-3">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
      <span v-if="props.member.title_id">{{ props.member.title.title }}</span> {{ member_name }}
    </h5>

    <div class="flex mb-3">
      <Badge type="green" v-if="props.member.membership_application_status">
        {{ props.member.membership_application_status.title }}</Badge>
      <Badge type="green" v-else>Draft</Badge>
      <Badge type="green">{{ props.member.membership_type.title }}</Badge>
    </div>

    <p class="font-normal text-gray-700 dark:text-gray-400">
      {{ props.member.job_title }}, {{ props.member.current_employer }}
    </p>
  </the-card>
</template>
