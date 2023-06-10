<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import AuditLogTable from '@/Components/AuditLogTable.vue'
import {
  mdiSecurity,
} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";


defineProps(['auditLog', 'member_id'])
</script>

<template>
  <LayoutAuthenticated title="Audit Log">
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiSecurity" title="Audig Log" main> &nbsp;
      </SectionTitleLineWithButton>
      <AuditLogTable :audit-log="auditLog" :member_id="member_id" />
    </SectionMain>
  </LayoutAuthenticated>
</template>
