<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { mdiViewList } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import TableUsers from '@/Components/TableUsers.vue'

defineProps(['filters', 'users'])
</script>

<template>
  <LayoutAuthenticated title="Users">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiViewList" title="Users" main> &nbsp; </SectionTitleLineWithButton>
      <TableUsers :filters="filters" :users="users" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
