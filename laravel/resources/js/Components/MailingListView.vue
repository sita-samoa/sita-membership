<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, watch, reactive } from 'vue'
import { Dropdown, ListGroup, ListGroupItem, Badge, Button } from 'flowbite-vue'
import ClipboardIcon from 'vue-material-design-icons/Clipboard.vue'
import ClipboardMultipleIcon from 'vue-material-design-icons/ClipboardMultiple.vue'
import { mdiEmailMinus, mdiEmailPlus } from '@mdi/js'
import TooltipTrigger from '@/Components/TooltipTrigger.vue'
import Pagination from '@/Components/Pagination.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import utc from 'dayjs/plugin/utc'

dayjs.extend(relativeTime)
dayjs.extend(utc)

const props = defineProps({
  mailingLists: Object,
  memberTypes: Object,
  members: Object,
  membershipTypeId: Number,
  mailingId: Number,
  allEmails: String,
  subData: Object,
})

const filterName = computed(() => {
  const status = parseInt(filterStatus.value)
  const ml = props.mailingLists.find(ml => ml.id == status)
  return capitalize(ml.code)
})

const subStats = computed(() => {
  const { month_subs, last_month_subs, month_unsubs, last_month_unsubs } = props.subData
  const subsPercentage = ((month_subs - last_month_subs) / last_month_subs) * 100
  const unsubsPercentage = ((month_unsubs - last_month_unsubs) / last_month_unsubs) * 100
  const monthLabel = dayjs().format('MMMM')
  return [
    {
      trend: Math.abs(subsPercentage).toFixed(2) + '%',
      trendType: subsPercentage < 0 ? 'down' : subsPercentage > 0 ? 'up' : null,
      icon: mdiEmailPlus,
      color: 'text-green-500',
      number: month_subs,
      label: `New Subscribers - ${monthLabel}`,
    },
    {
      trend: Math.abs(unsubsPercentage).toFixed(2) + '%',
      trendType: unsubsPercentage < 0 ? 'up' : unsubsPercentage > 0 ? 'down' : null,
      icon: mdiEmailMinus,
      color: 'text-red-500',
      number: month_unsubs,
      label: `Opted Out - ${monthLabel}`,
    },
  ]
})

const capitalize = str => str.replace(/\b\w/g, l => l.toUpperCase())

const filterStatus = ref(props.mailingId ?? 1)

const membershipTypeId = ref(props.membershipTypeId ?? 0)

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

function getMembershipType(id) {
  if (id === 0) return 'All'
  return props.memberTypes.find(mt => mt.id === id).title
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

function copySingleEmail(email) {
  navigator.clipboard.writeText(email)
}

function copyAllEmails() {
  navigator.clipboard.writeText(props.allEmails)
}

function getSubscriptionDate(date) {
  return dayjs(date).fromNow()
}

watch([filterStatus, membershipTypeId], ([filterStatusValue, membershipTypeIdValue]) => {
  router.get(
    route('mailing-lists.index'),
    {
      id: filterStatusValue,
      membershipTypeId: membershipTypeIdValue,
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
    <div v-if="subStats != null" class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <CardBoxWidget :trend="subStats[0].trend" :trend-type="subStats[0].trendType" :color="subStats[0].color" :icon="subStats[0].icon" :number="subStats[0].number" :label="subStats[0].label" :showSecondaryIcon="false" />
      <CardBoxWidget :trend="subStats[1].trend" :trend-type="subStats[1].trendType" :color="subStats[1].color" :icon="subStats[1].icon" :number="subStats[1].number" :label="subStats[1].label" :showSecondaryIcon="false" />
    </div>
    <div class="flex justify-between w-full h-auto items-center">
      <div>
        <!-- Filter dropdown -->
        <dropdown :text="filterName" class="mt-3">
          <list-group>
            <list-group-item v-for="list in props.mailingLists" :key="list.id" @click="filterStatus = list.id">
              {{ capitalize(list.code) }}
            </list-group-item>
          </list-group>
        </dropdown>
        <dropdown :text="getMembershipType(membershipTypeId)" class="mt-3 md:ml-3">
          <list-group>
            <list-group-item :key="0" @click="membershipTypeId = 0"> All </list-group-item>
            <list-group-item v-for="type in props.memberTypes" :key="type.id" @click="membershipTypeId = type.id">
              {{ type.title }}
            </list-group-item>
          </list-group>
        </dropdown>
      </div>
      <TooltipTrigger @trigger="() => copyAllEmails()" :duration="1000" text="Copied All">
        <Button color="green" size="lg">
          <div class="flex"><clipboard-multiple-icon></clipboard-multiple-icon>&nbsp;Copy all emails</div>
        </Button>
      </TooltipTrigger>
    </div>
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
                        <TooltipTrigger @trigger="() => copySingleEmail(member.home_email)" :show="false" :duration="800" text="Copied">
                          <clipboard-icon class="w-5 h-5 cursor-pointer text-emerald-500 dark:text-slate-300" />
                        </TooltipTrigger>
                      </td>
                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <Badge type="default">{{ getMembershipType(member.membership_type_id) ?? 'N/A' }}</Badge>
                        </div>
                      </td>
                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <Badge :type="getBadge(member.membership_status_id)">{{ applicationStatus[member.membership_status_id] }}</Badge>
                        </div>
                      </td>

                      <td class="px-4 py-4 text-sm whitespace-nowrap">
                        <div>
                          <h4 class="text-gray-700 dark:text-gray-200">{{ getSubscriptionDate(member.mailing_lists[0].pivot.updated_at) }}</h4>
                        </div>
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
