<script setup>
import MemberSummary from './MemberSummary.vue';
defineProps(['list'])
</script>
<template>
<MemberSummary v-for="member in list" :member="member" />
</template>
