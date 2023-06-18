<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import { mdiEmail } from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
const props = defineProps({
  mailingLists: Object,
  members: Object
})

</script>
<template>
  <LayoutAuthenticated title="Mailing Lists">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiEmail" title="Mailing Lists" main> &nbsp; </SectionTitleLineWithButton>
      
    </SectionMain>
  </LayoutAuthenticated>
</template>