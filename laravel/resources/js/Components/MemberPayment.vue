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

defineProps(['statuses'])

</script>
<template>
  <fwb-table>
    <fwb-table-head>
      <fwb-table-head-cell>From Date</fwb-table-head-cell>
      <fwb-table-head-cell>To Date</fwb-table-head-cell>
      <fwb-table-head-cell>Receipt Number</fwb-table-head-cell>
      <fwb-table-head-cell>
        <span class="sr-only">Edit</span>
      </fwb-table-head-cell>
    </fwb-table-head>
    <fwb-table-body>
      <fwb-table-row v-for="(c, index) in statuses" >
        <fwb-table-cell>{{ c.from_date }}</fwb-table-cell>
        <fwb-table-cell>{{ c.to_date }}</fwb-table-cell>
        <fwb-table-cell>{{ c.receipt_number }}</fwb-table-cell>
        <fwb-table-cell>
          <fwb-a href="#">
            Edit
          </fwb-a>
        </fwb-table-cell>
      </fwb-table-row>
    </fwb-table-body>
  </fwb-table>
</template>
