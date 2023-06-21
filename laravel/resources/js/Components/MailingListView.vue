<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { Dropdown, ListGroup, ListGroupItem, Badge, Tooltip } from 'flowbite-vue'
import ClipboardIcon from 'vue-material-design-icons/Clipboard.vue'
import Pagination from '@/Components/Pagination.vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import utc from 'dayjs/plugin/utc'

dayjs.extend(relativeTime)
dayjs.extend(utc)

const props = defineProps({
  mailingLists: Object,
  members: Object,
  mailingId: Number,
})

const filterName = computed(() => {
  const status = parseInt(filterStatus.value)
  const ml = props.mailingLists.find(ml => ml.id == status)
  return capitalize(ml.code)
})

const capitalize = str => str.replace(/\b\w/g, l => l.toUpperCase())

const filterStatus = ref(props.mailingId ?? 1)

const currentMailingList = computed(() => {
  return props.mailingLists.find(ml => ml.id == filterStatus.value)
})

function getBadge(application_status_id) {
  let badgeType = 'default'
  switch (Number(application_status_id)) {
    case 1:
      // draft
      badgeType = 'yellow'
      break
    case 2:
      // submitted
      badgeType = 'pink'
      break
    case 3:
      // endorsed
      badgeType = 'dark'
      break
    case 4:
      // accepted
      badgeType = 'green'
      break
  }
  return badgeType
}

const applicationStatus = {
  1: 'Draft',
  2: 'Submitted',
  3: 'Endorsed',
  4: 'Accepted',
  5: 'Lapsed',
  6: 'Expired',
  7: 'Banned',
}

const membershipType = {
  1: 'Full',
  2: 'Associate',
  3: 'Affiliate',
  4: 'Student',
  5: 'Fellow',
}

function copySingleEmail(email) {
  navigator.clipboard.writeText(email)
}

function getMemberMailingListPreferenceDateField(value) {
  if (value) {
    return 'updated_at'
  } else {
    return 'created_at'
  }
}

function getSubscriptionDate(date) {
  return dayjs(date).fromNow()
}

watch(filterStatus, value => {
  router.get(
    route('mailing-lists.index'),
    {
      id: value,
    },
    {
      preserveState: true,
    }
  )
})
</script>
<template>
  <div class="grid grid-cols-1 gap-6 mb-6">
    <h1 class="text-xl bold mb-0 pb-0">{{ currentMailingList.title }}</h1>
    <!-- Filter dropdown -->
    <dropdown :text="filterName" class="mt-3">
      <list-group>
        <list-group-item v-for="list in props.mailingLists" :key="list.id" @click="filterStatus = list.id">
          {{ capitalize(list.code) }}
        </list-group-item>
      </list-group>
    </dropdown>
    <!-- No results message -->
    <div v-if="props.members.data.length > 0">
      <div class="mb-3">Showing {{ props.members.from }} to {{ props.members.to }} of {{ props.members.total }} results.</div>
      <section class="min-w-full pr-4 mx-auto">
        <div class="flex flex-col mt-3 mb-3">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                      <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <button class="flex items-center gap-x-3 focus:outline-none">
                          <span>Name</span>
                        </button>
                      </th>

                      <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Email Address</th>

                      <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Membership Type</th>

                      <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Status</th>

                      <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">Subscribed</th>

                      <th scope="col" class="relative py-3.5 px-4">
                        <span class="sr-only">Edit</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                    <tr v-for="member in props.members.data" :key="member.id">
                      <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                        <div>
                          <h2 class="font-medium text-gray-800 dark:text-white">{{ member.first_name }} {{ member.last_name }}</h2>
                        </div>
                      </td>
                      <td class="px-12 py-4 text-sm font-medium whitespace-nowrap flex w-auto justify-start items-center">
                        <div class="inline px-3 py-1 w-auto text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-gray-800">
                          {{ member.home_email }}
                        </div>
                        <Tooltip trigger="click">
                          <template #trigger>
                            <clipboard-icon class="w-5 h-5 cursor-pointer text-emerald-500 dark:text-slate-300" @click="() => copySingleEmail(member.home_email)" />
                          </template>
                          <template #content>
                            <span>Copied</span>
                          </template>
                        </Tooltip>
                      </td>
                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <Badge type="default">{{ membershipType[member.membership_type_id] }}</Badge>
                        </div>
                      </td>
                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <Badge :type="getBadge(member.membership_status_id)">{{ applicationStatus[member.membership_status_id] }}</Badge>
                        </div>
                      </td>

                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <h4 class="text-gray-700 dark:text-gray-200">{{ getSubscriptionDate(member.mailing_lists[0].pivot[getMemberMailingListPreferenceDateField(member.mailing_lists[0].pivot.updated_at)]) }}</h4>
                        </div>
                      </td>

                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <button class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg dark:text-gray-300 hover:bg-gray-100">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <Pagination :links="props.members.links" />
      </section>
    </div>
    <div v-else>
      <p>No members are subscribed to this mailing list.</p>
    </div>
  </div>
</template>
