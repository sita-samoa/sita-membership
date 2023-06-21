<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { mdiAccountGroup } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import TableUsers from '@/Components/TableUsers.vue'

defineProps(['filters', 'users'])
</script>

<template>
  <LayoutAuthenticated title="Users">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Users" main> &nbsp; </SectionTitleLineWithButton>
      <TableUsers :filters="filters" :users="users" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
