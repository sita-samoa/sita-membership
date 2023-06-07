<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import AuditLogTable from '../../Components/AuditLogTable.vue'

defineProps(['auditLog', 'member_id'])
</script>

<template>
  <LayoutAuthenticated title="Audit Log">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Audit Log</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <AuditLogTable :audit-log="auditLog" :member_id="member_id" />
        </div>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
