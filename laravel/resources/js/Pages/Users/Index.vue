<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import MembersList from '@/Components/MembersList.vue'
import { mdiViewList } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'

defineProps(['member', 'members'])
</script>

<template>
  <LayoutAuthenticated title="Users">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiViewList" title="Users" main> &nbsp; </SectionTitleLineWithButton>
      Hello
    </SectionMain>
  </LayoutAuthenticated>
</template>
