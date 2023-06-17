<script setup>
import { router, usePage, useForm } from '@inertiajs/vue3'
import { computed, onMounted, watch } from 'vue'
import { Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'
import debounce from 'lodash/debounce'
import MemberSummaryCard from '@/Components/MemberSummaryCard.vue'
import Pagination from '@/Components/Pagination.vue'
import AccountCircleIcon from 'vue-material-design-icons/AccountCircle.vue'
import FileIcon from 'vue-material-design-icons/File.vue'
import SendCheckIcon from 'vue-material-design-icons/SendCheck.vue'
import DecagramIcon from 'vue-material-design-icons/Decagram.vue'
import CheckDecagramIcon from 'vue-material-design-icons/CheckDecagram.vue'
import ClockAlertOutlineIcon from 'vue-material-design-icons/ClockAlertOutline.vue'
import ClockOutlineIcon from 'vue-material-design-icons/ClockOutline.vue'
import AccountOffIcon from 'vue-material-design-icons/AccountOff.vue'
import SearchFilter from '@/Components/SearchFilter.vue'

import { initDropdowns } from 'flowbite'

// initialize components based on data attribute selectors
onMounted(() => {
  initDropdowns()
})

const props = defineProps({
  list: {
    Type: Object,
    Default: {},
  },
})

const filterName = computed(() => {
  const status = parseInt(form.membership_status_id)
  switch (status) {
    case 1:
      return 'Draft'
    case 2:
      return 'Submitted'
    case 3:
      return 'Endorsed'
    case 4:
      return 'Accepted'
    case 5:
      return 'Lapsed'
    case 6:
      return 'Expired'
    case 7:
      return 'Banned'
  }
  return 'All'
})

const form = useForm({
  search: usePage().props.filters.search,
  membership_status_id: usePage().props.filters.membership_status_id || '',
})

function reset() {
  // form.reset()
  form.search = ''
  form.membership_status_id = ''
}

watch(
  () => form,
  debounce(function () {
    form.get(
      route('members.index'),
      {
        search: form.search,
        membership_status_id: form.membership_status_id,
      },
      { preserveState: true }
    )
  }, 150),
  { deep: true }
)
</script>
<template>
  <SearchFilter v-model="form.search" :displayText="'Show - ' + filterName" @reset="reset">
    <list-group>
      <list-group-item @click="form.membership_status_id = ''">
        <template #prefix>
          <AccountCircleIcon />
        </template>
        All
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 1">
        <template #prefix>
          <FileIcon />
        </template>
        Draft
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 2">
        <template #prefix>
          <SendCheckIcon />
        </template>
        Submitted
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 3">
        <template #prefix>
          <DecagramIcon />
        </template>
        Endorsed
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 4">
        <template #prefix>
          <CheckDecagramIcon />
        </template>
        Accepted
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 5">
        <template #prefix>
          <ClockOutlineIcon />
        </template>
        Lapsed
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 6">
        <template #prefix>
          <ClockAlertOutlineIcon />
        </template>
        Expired
      </list-group-item>
      <list-group-item @click="form.membership_status_id = 7">
        <template #prefix>
          <AccountOffIcon />
        </template>
        Banned
      </list-group-item>
    </list-group>
  </SearchFilter>

  <!-- No results message -->
  <div v-if="!props.list.total">No matches found. Try changing the filter.</div>
  <div v-else class="mb-3">
    <span v-if="props.list.total === 1"> Showing 1 result. </span>
    <span v-else> Showing {{ props.list.from }} to {{ props.list.to }} of {{ props.list.total }} results. </span>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <MemberSummaryCard v-for="member in props.list.data" :key="member.id" :member="member" />
  </div>

  <!-- Pagination -->
  <Pagination :links="props.list.links" />
</template>
