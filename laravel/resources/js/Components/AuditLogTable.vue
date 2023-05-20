
<script setup>
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)

defineProps({
  auditLog: {
    type: Object,
    default: {}
  }
})

const auditLog1 = [{
  "id": 1,
  "user_type": "App\\Models\\User",
  "user_id": 1,
  "event": "updated",
  "auditable_type": "App\\Models\\Member",
  "auditable_id": 227,
  "old_values": {
    "title_id": 2,
    "first_name": "Abbigail",
    "last_name": "Aufderhar",
    "dob": null,
    "gender_id": 2,
    "job_title": "Physician",
    "current_employer": "Langworth Inc"
  },
  "new_values": {
    "title_id": 3,
    "first_name": "Tempora iusto labore",
    "last_name": "Voluptas aliqua Vol",
    "dob": "2003-04-25",
    "gender_id": 3,
    "job_title": "Ut qui tempor rerum",
    "current_employer": "Est dolorem consequ"
  },
  "url": "http://sita-membership.docker.localhost:8000/members/227",
  "ip_address": "192.168.48.8",
  "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0",
  "tags": null,
  "created_at": "2023-05-20T02:43:08.000000Z",
  "updated_at": "2023-05-20T02:43:08.000000Z",
  "user": {
    "id": 1,
    "name": "admin",
    "email": "admin@example.com",
    "email_verified_at": "2023-05-20T02:40:01.000000Z",
    "two_factor_confirmed_at": null,
    "current_team_id": 1,
    "profile_photo_path": null,
    "created_at": "2023-05-20T02:40:01.000000Z",
    "updated_at": "2023-05-20T02:42:55.000000Z",
    "profile_photo_url": "https://ui-avatars.com/api/?name=a&color=7F9CF5&background=EBF4FF"
  }
}, {
  "id": 2,
  "user_type": "App\\Models\\User",
  "user_id": 1,
  "event": "updated",
  "auditable_type": "App\\Models\\Member",
  "auditable_id": 227,
  "old_values": {
    "other_membership": null
  },
  "new_values": {
    "other_membership": "something"
  },
  "url": "http://sita-membership.docker.localhost:8000/members/227",
  "ip_address": "192.168.48.8",
  "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/112.0",
  "tags": null,
  "created_at": "2023-05-20T02:48:34.000000Z",
  "updated_at": "2023-05-20T02:48:34.000000Z",
  "user": {
    "id": 1,
    "name": "admin",
    "email": "admin@example.com",
    "email_verified_at": "2023-05-20T02:40:01.000000Z",
    "two_factor_confirmed_at": null,
    "current_team_id": 1,
    "profile_photo_path": null,
    "created_at": "2023-05-20T02:40:01.000000Z",
    "updated_at": "2023-05-20T02:42:55.000000Z",
    "profile_photo_url": "https://ui-avatars.com/api/?name=a&color=7F9CF5&background=EBF4FF"
  }
}]
</script>
<template>

  <h4 class="text-xl my-3">Audit log</h4>
  <div class="relative overflow-x-auto">
      <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                  <th scope="col" class="px-6 py-3">
                      Event
                  </th>
                  <th scope="col" class="px-6 py-3">
                      User
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Date
                  </th>
                  <th scope="col" class="px-6 py-3">
                      Address
                  </th>
              </tr>
          </thead>
          <tbody>
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                v-for="a in auditLog">
                  <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                      {{ a.event }}
                  </th>
                  <td class="px-6 py-4">
                      {{ a.user.name }}
                  </td>
                  <td class="px-6 py-4">
                      {{ dayjs(a.created_at).fromNow() }}
                  </td>
                  <td class="px-6 py-4">
                      {{ a.ip_address }}
                  </td>
              </tr>
          </tbody>
      </table>
  </div>

</template>

