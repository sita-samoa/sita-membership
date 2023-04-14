<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { TheCard } from 'flowbite-vue'

</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Signup
      </h2>
    </template>

    <div class="py-12">
      <the-card href="#" cardClasses="w-full">
        <h5
          class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
          Welcome</h5>
        <p class="font-normal text-gray-700 dark:text-gray-400">
          Click the signup button to begin.
        </p>
      </the-card>
    </div>
  </AppLayout>
</template>
