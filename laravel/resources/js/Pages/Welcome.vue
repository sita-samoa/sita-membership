<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { FwbCard } from 'flowbite-vue'
import ApplicationMark from '@/Components/ApplicationMark.vue'
import LayoutGuestAlt from '@/Layouts/LayoutGuestAlt.vue'
import BaseIcon from '@/Components/BaseIcon.vue'
import { useStyleStore } from '@/Stores/style.js'
import { mdiThemeLightDark } from '@mdi/js'
import FooterBar from '@/Components/FooterBar.vue'

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  appVersion: String,
})

const styleStore = useStyleStore()
</script>

<template>
  <Head title="Join SITA" />
  <LayoutGuestAlt :canLogin="canLogin" :canRegister="canRegister" :appVersion="appVersion">

    <div class="flex justify-center">
      <ApplicationMark />
    </div>

    <div class="my-16">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        <fwb-card href="#" variant="horizontal" img-src="/imgs/sita_cover.webp" img-alt="Desk">
          <div class="p-5">
            <Link :href="route('dashboard.index')">
              <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Join SITA</h5>
              <p class="font-normal text-gray-700 dark:text-gray-400">You've reached the SITA Online portal. Use it to Sign up and join the Sāmoa Information Technology Association. Register an account to get started or login to complete your Sign up.</p>
            </Link>
          </div>
        </fwb-card>

        <fwb-card href="#" variant="horizontal" img-src="/imgs/poly-laptop.webp" img-alt="Desk">
          <div class="p-5">
            <Link :href="route('members-list.index')">
              <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Search Members List</h5>
              <p class="font-normal text-gray-700 dark:text-gray-400">Verify the status of our members. Check their membership details, their area of interest, and their skills. Search for members by name, email, or skills.</p>
            </Link>
          </div>
        </fwb-card>
      </div>
    </div>
  </LayoutGuestAlt>
</template>

<style>
.bg-dots-darker {
  background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
  .dark\:bg-dots-lighter {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
  }
}
</style>
