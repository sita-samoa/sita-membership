<script setup>
import {
  FwbA,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'

const allowEdit = false

defineProps(['statuses'])

</script>
<template>
  <fwb-table v-if="statuses.length > 0">
    <fwb-table-head>
      <fwb-table-head-cell>From Date</fwb-table-head-cell>
      <fwb-table-head-cell>To Date</fwb-table-head-cell>
      <fwb-table-head-cell>Receipt Number</fwb-table-head-cell>
      <fwb-table-head-cell v-if="allowEdit">
        <span class="sr-only">Edit</span>
      </fwb-table-head-cell>
    </fwb-table-head>
    <fwb-table-body>
      <fwb-table-row v-for="(c, index) in statuses" >
        <fwb-table-cell>{{ c.from_date }}</fwb-table-cell>
        <fwb-table-cell>{{ c.to_date }}</fwb-table-cell>
        <fwb-table-cell>{{ c.receipt_number }}</fwb-table-cell>
        <fwb-table-cell v-if="allowEdit">
          <fwb-a href="#">
            Edit
          </fwb-a>
        </fwb-table-cell>
      </fwb-table-row>
    </fwb-table-body>
  </fwb-table>
  <div v-else class="text-sm">Receipt numbers will be displayed here.</div>
</template>
