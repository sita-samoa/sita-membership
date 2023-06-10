<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { Dropdown, ListGroup, ListGroupItem } from 'flowbite-vue'
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

const props = defineProps({
  list: {
    Type: Object,
    Default: {},
  },
})

const filterName = computed(() => {
  const status = parseInt(filterStatus.value)
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

const filterStatus = ref(usePage().props.filters.membership_status_id)

watch(filterStatus, value => {
  router.get(
    route('members.index'),
    {
      membership_status_id: value,
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
          <AccountCircleIcon />
        </template>
        All
      </list-group-item>
      <list-group-item @click="filterStatus = 1">
        <template #prefix>
          <FileIcon />
        </template>
        Draft
      </list-group-item>
      <list-group-item @click="filterStatus = 2">
        <template #prefix>
          <SendCheckIcon />
        </template>
        Submitted
      </list-group-item>
      <list-group-item @click="filterStatus = 3">
        <template #prefix>
          <DecagramIcon />
        </template>
        Endorsed
      </list-group-item>
      <list-group-item @click="filterStatus = 4">
        <template #prefix>
          <CheckDecagramIcon />
        </template>
        Accepted
      </list-group-item>
      <list-group-item @click="filterStatus = 5">
        <template #prefix>
          <ClockOutlineIcon />
        </template>
        Lapsed
      </list-group-item>
      <list-group-item @click="filterStatus = 6">
        <template #prefix>
          <ClockAlertOutlineIcon />
        </template>
        Expired
      </list-group-item>
      <list-group-item @click="filterStatus = 7">
        <template #prefix>
          <AccountOffIcon />
        </template>
        Banned
      </list-group-item>
    </list-group>
  </dropdown>

  <!-- No results message -->
  <div v-if="!props.list.total">No matches found. Try changing the filter.</div>
  <div v-else class="mb-3">Showing {{ props.list.from }} to {{ props.list.to }} of {{ props.list.total }} results.</div>


  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <MemberSummaryCard v-for="member in props.list.data" :key="member.id" :member="member" />
  </div>

  <!-- Pagination -->
  <Pagination :links="props.list.links" />
</template>
