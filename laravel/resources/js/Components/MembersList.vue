<script setup>
import { router, usePage, useForm, Link } from '@inertiajs/vue3'
import { computed, onMounted, watch } from 'vue'
import { Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'
import debounce from 'lodash/debounce'
import MemberSummaryCard from '@/Components/MemberSummaryCard.vue'
import Pagination from '@/Components/Pagination.vue'
import { mdiDownload, mdiSendCheck, mdiFile, mdiCheckDecagram, mdiClockOutline, mdiDecagram, mdiClockAlertOutline, mdiAccountOff, mdiConsoleLine } from '@mdi/js'
import AccountCircleIcon from 'vue-material-design-icons/AccountCircle.vue'
import FileIcon from 'vue-material-design-icons/File.vue'
import SendCheckIcon from 'vue-material-design-icons/SendCheck.vue'
import DecagramIcon from 'vue-material-design-icons/Decagram.vue'
import CheckDecagramIcon from 'vue-material-design-icons/CheckDecagram.vue'
import ClockAlertOutlineIcon from 'vue-material-design-icons/ClockAlertOutline.vue'
import ClockOutlineIcon from 'vue-material-design-icons/ClockOutline.vue'
import AccountOffIcon from 'vue-material-design-icons/AccountOff.vue'
import SearchFilter from '@/Components/SearchFilter.vue'
import BaseLevel from '@/Components/BaseLevel.vue'
import BaseIcon from '@/Components/BaseIcon.vue'
import AccountCancelIcon from 'vue-material-design-icons/AccountCancel.vue'

import { initDropdowns } from 'flowbite'
import ResultsSummary from './ResultsSummary.vue'

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
    case 8:
      return 'Rejected'
  }
  return 'All'
})

const form = useForm({
  search: usePage().props.filters.search,
  membership_status_id: usePage().props.filters.membership_status_id || '',
})

function download() {
  const search = form.search || ''
  const membership_status_id = form.membership_status_id || ''
  const url = route('members.export') + '?search=' + search + '&membership_status_id=' + membership_status_id
  // Do this because inertia form.get does not work
  window.open(url, '_blank')
}

function reset() {
  form.search = ''
  form.membership_status_id = ''
}

watch(
  () => form,
  debounce(function () {
    form.get(route('members.index'), {
      search: form.search,
      membership_status_id: form.membership_status_id,
    })
  }, 500),
  { deep: true }
)
</script>
<template>
  <SearchFilter v-model="form.search" :display-text="'Show - ' + filterName" placeholder="Search by name, job  or employer" @reset="reset">
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
      <list-group-item @click="form.membership_status_id = 8">
        <template #prefix>
          <AccountCancelIcon />
        </template>
        Rejected
      </list-group-item>
    </list-group>
  </SearchFilter>

  <!-- Results summary -->
  <BaseLevel mobile>
    <ResultsSummary :total="props.list.total" :from="props.list.from" :to="props.list.to" />
    <div class="mb-3 text-small" @click="download">
      <a title="Download" href="#"> <BaseIcon :path="mdiDownload" /> Download </a>
    </div>
  </BaseLevel>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <MemberSummaryCard v-for="member in props.list.data" :key="member.id" :member="member" />
  </div>

  <!-- Pagination -->
  <Pagination :links="props.list.links" />
</template>
