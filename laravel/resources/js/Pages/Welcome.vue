<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { TheCard } from 'flowbite-vue'
import ApplicationMark from '@/Components/ApplicationMark.vue'
import LayoutGuest from '@/Layouts/LayoutGuest.vue'
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
  <LayoutGuest>
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
      <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        <a @click="styleStore.setDarkMode()" href="#" title="Light/Dark Mode" class="mr-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"><BaseIcon :path="mdiThemeLightDark" /></a>
        <Link v-if="$page.props.auth.user" :href="route('dashboard.index')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</Link>

        <template v-else>
          <Link :href="route('login')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</Link>

          <Link v-if="canRegister" :href="route('register')" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</Link>
        </template>
      </div>

      <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
          <ApplicationMark />
        </div>

        <div class="my-16">
          <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8">
            <the-card href="#" variant="horizontal" img-src="/imgs/sita_cover.webp" img-alt="Desk">
              <Link :href="route('dashboard.index')">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Join SITA</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">You've reached the SITA Online portal. Use it to Sign up and join the Sāmoa Information Technology Association. Register an account to get started or login to complete your Sign up.</p>
              </Link>
            </the-card>
          </div>
        </div>

        <FooterBar />
      </div>
    </div>
  </LayoutGuest>
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
