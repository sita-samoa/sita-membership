<script setup>
import { Head, Link } from '@inertiajs/vue3'
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

        <slot />

        <FooterBar />
      </div>
    </div>
  </LayoutGuest>
</template>
