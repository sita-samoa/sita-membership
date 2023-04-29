<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'
import MemberSummaryCard from '@/Components/MemberSummaryCard.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  list: {
    Type: Object,
    Default: {},
  }
})

const filterName = computed(() => {
  switch(filterStatus.value) {
    case 1:
      return "Draft"
    case 2:
      return "Submitted"
    case 3:
      return "Endorsed"
    case 4:
      return "Accepted"
  }
  return "All"
})

const filterStatus = ref()

watch(filterStatus, (value) => {
  router.get(
    route('members.index'),
    {
      membership_application_status_id: value
    },
    {
      preserveState: true,
    }
  )
})

</script>
<template>
<!-- Filter dropdown -->
<dropdown :text="'Show - ' + filterName" class="mb-3">
  <list-group>
    <list-group-item @click="filterStatus = ''">
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path></svg>
      </template>
      All
    </list-group-item>
    <list-group-item @click="filterStatus = 1">
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path></svg>
      </template>
      Draft
    </list-group-item>
    <list-group-item @click="filterStatus = 2">
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"></path></svg>
      </template>
      Submitted
    </list-group-item>
    <list-group-item @click="filterStatus = 3">
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z" clip-rule="evenodd"></path></svg>
      </template>
      Endorsed
    </list-group-item>
    <list-group-item @click="filterStatus = 4">
      <template #prefix>
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z" clip-rule="evenodd"></path></svg>
      </template>
      Accepted
    </list-group-item>
  </list-group>
</dropdown>

<!-- No results message -->
<div v-if="!props.list.total">No matches found. Try changing the filter.</div>
<div v-else class="mb-3">
  Showing {{ props.list.from }} to {{ props.list.to }} of {{ props.list.total }} results.
</div>

<div class="flex flex-wrap">
  <!-- Member list-->
  <MemberSummaryCard class="mb-3 mr-3" v-for="member in props.list.data" :key="member.id" :member="member" />
</div>

<!-- Pagination -->
<Pagination :links="props.list.links" />
</template>
