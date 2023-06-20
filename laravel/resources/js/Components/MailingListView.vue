<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { Dropdown, ListGroup, ListGroupItem, Badge } from 'flowbite-vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import utc from 'dayjs/plugin/utc'
import SectionTitle from '@/Components/SectionTitle.vue'

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

const capitalize = (str) => str.replace(/\b\w/g, l => l.toUpperCase())

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
  7: 'Banned'
}

const membershipType = {
  1: 'Full',
  2: 'Associate',
  3: 'Affiliate',
  4: 'Student',
  5: 'Fellow'
}

function getSubscriptionDate(date){
  return dayjs(date).local().fromNow(dayjs())
}

watch(filterStatus, value => {
  router.get(
    route('mailing-lists.index'),
    {
      id: value
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
        <list-group-item :key="list.id" v-for="list in props.mailingLists" @click="filterStatus = list.id">
          {{ capitalize(list.code) }}
        </list-group-item>
      </list-group>
    </dropdown>
    <!-- <MemberSummaryCard v-for="member in props.members" :key="member.id" :member="member" /> -->

    <!-- No results message -->
    <div v-if="props.members.length > 0">

      <!-- <div v-else class="mb-3">Showing {{ props.list.from }} to {{ props.list.to }} of {{ props.list.total }} results.</div> -->
      <section class="min-w-full pr-4 mx-auto">
        <div class="flex flex-col mt-3">
          <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
              <div class="overflow-hidden border border-gray-200 dark:border-gray-700 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                      <th scope="col"
                        class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <button class="flex items-center gap-x-3 focus:outline-none">
                          <span>Name</span>
                        </button>
                      </th>

                      <th scope="col"
                        class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        Email Address
                      </th>

                      <th scope="col"
                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        Membership Type
                      </th>

                      <th scope="col"
                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        Status</th>

                      <th scope="col"
                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        Subscribed</th>

                      <th scope="col" class="relative py-3.5 px-4">
                        <span class="sr-only">Edit</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                    <tr :key="member.id" v-for="member in props.members">
                      <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                        <div>
                          <h2 class="font-medium text-gray-800 dark:text-white ">{{ member.first_name }} {{
                            member.last_name
                          }}</h2>
                        </div>
                      </td>
                      <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                        <div
                          class="inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2 bg-emerald-100/60 dark:bg-gray-800">
                          {{ member.home_email }}
                        </div>
                      </td>
                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <Badge type="default">{{
                            membershipType[member.membership_type_id] }}</Badge>
                        </div>
                      </td>
                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <Badge :type="getBadge(member.membership_status_id)">{{
                            applicationStatus[member.membership_status_id] }}</Badge>
                        </div>
                      </td>

                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <h4 class="text-gray-700 dark:text-gray-200">{{ getSubscriptionDate(member.pivot.updated_at)  }}</h4>
                        </div>
                      </td>

                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <button
                          class="px-1 py-1 text-gray-500 transition-colors duration-200 rounded-lg dark:text-gray-300 hover:bg-gray-100">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
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

        <div class="mt-6 sm:flex sm:items-center sm:justify-between ">
          <div class="text-sm text-gray-500 dark:text-gray-400">
            Page <span class="font-medium text-gray-700 dark:text-gray-100">1 of 10</span>
          </div>

          <div class="flex items-center mt-4 gap-x-4 sm:mt-0">
            <a href="#"
              class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
              </svg>

              <span>
                previous
              </span>
            </a>

            <a href="#"
              class="flex items-center justify-center w-1/2 px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md sm:w-auto gap-x-2 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800">
              <span>
                Next
              </span>

              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
              </svg>
            </a>
          </div>
        </div>
      </section>
    </div>
    <div v-else>
      <p>No members are subscribed to this mailing list.</p>
    </div>
  </div>

  <!-- Pagination -->
  <!-- <Pagination :links="props.list.links" /> --></template>