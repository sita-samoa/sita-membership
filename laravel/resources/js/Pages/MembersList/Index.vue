<script setup>
import MembersList from '@/Components/MembersList.vue'
import { mdiViewList } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { Head } from '@inertiajs/vue3'
import LayoutGuestAlt from '@/Layouts/LayoutGuestAlt.vue'
import { useStyleStore } from '@/Stores/style.js'

defineProps({
  members: Object,
  canLogin: Boolean,
  canRegister: Boolean,
  appVersion: String,
})

const styleStore = useStyleStore()
</script>

<template>
  <Head title="Members List" />
  <LayoutGuestAlt :canLogin="true" :canRegister="true">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiViewList" title="Members List" main> &nbsp; </SectionTitleLineWithButton>
      <MembersList :list="members" searchRoute="members-list.index" />
    </SectionMain>
  </LayoutGuestAlt>
</template>
