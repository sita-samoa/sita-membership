<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import MembersList from '@/Components/MembersList.vue'
import { mdiViewList } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'

defineProps(['member', 'members'])
</script>

<template>
  <LayoutAuthenticated title="Members">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiViewList" title="Members" main> &nbsp; </SectionTitleLineWithButton>
      <MembersList :list="members" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
