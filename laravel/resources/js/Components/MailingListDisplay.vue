<script setup>
import { mdiEmailCheckOutline } from '@mdi/js'
import CardBoxComponentTitle from '@/Components/CardBoxComponentTitle.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'

const props = defineProps({
  mailingLists: Object,
})
</script>

<template>
  <CardBoxComponentTitle title="Mailing List Subscriptions" />
  <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
    <CardBoxWidget is-hoverable color="text-lime-500" :icon="mdiEmailCheckOutline" :number="props.mailingLists[1][1]" label="General" href="/mailing-lists?id=1" />
    <CardBoxWidget is-hoverable color="text-fuchsia-500" :icon="mdiEmailCheckOutline" :number="props.mailingLists[2][1]" label="Members" href="/mailing-lists?id=2" />
  </div>
</template>
