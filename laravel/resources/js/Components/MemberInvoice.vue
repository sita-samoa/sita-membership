<script setup>
import { Link } from '@inertiajs/vue3'
import { FwbA, FwbTable, FwbTableBody, FwbTableCell, FwbTableHead, FwbTableHeadCell, FwbTableRow } from 'flowbite-vue'

const allowEdit = false

const props = defineProps(['invoices', 'member_id'])

function download(id) {
  window.open(route('members.invoices.download.index', { invoice: id, member: props.member_id }))
}
</script>
<template>
  <h5 class="mb-3">Invoices</h5>
  <fwb-table v-if="invoices.length > 0">
    <fwb-table-head>
      <fwb-table-head-cell>Date</fwb-table-head-cell>
      <fwb-table-head-cell>Invoice Number</fwb-table-head-cell>
      <fwb-table-head-cell>Amount</fwb-table-head-cell>
      <fwb-table-head-cell>Pay Before</fwb-table-head-cell>
      <fwb-table-head-cell></fwb-table-head-cell>
      <fwb-table-head-cell v-if="allowEdit">
        <span class="sr-only">Edit</span>
      </fwb-table-head-cell>
    </fwb-table-head>
    <fwb-table-body>
      <fwb-table-row v-for="(c, index) in invoices">
        <fwb-table-cell>{{ c.invoice_date }}</fwb-table-cell>
        <fwb-table-cell>{{ c.invoice_number }}</fwb-table-cell>
        <fwb-table-cell class="text-right">${{ c.amount }}</fwb-table-cell>
        <fwb-table-cell>{{ c.pay_before_date }}</fwb-table-cell>
        <fwb-table-cell><Link href="#" @click="download(c.id)">Download</Link></fwb-table-cell>
        <fwb-table-cell v-if="allowEdit">
          <fwb-a href="#"> Edit </fwb-a>
        </fwb-table-cell>
      </fwb-table-row>
    </fwb-table-body>
  </fwb-table>
  <div v-else class="text-sm">Invoices will be displayed here.</div>
</template>
