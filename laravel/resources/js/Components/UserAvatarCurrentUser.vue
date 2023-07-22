<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import UserAvatar from '@/Components/UserAvatar.vue'

const avatar = computed(() => usePage().props.auth.user.profile_photo_url)
</script>

<template>
  <UserAvatar :avatar="avatar">
    <slot />
  </UserAvatar>
</template>
