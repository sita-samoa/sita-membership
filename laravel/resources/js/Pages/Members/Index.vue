<script setup>
import LayoutAuthenticated from '@/Layouts/LayoutAuthenticated.vue'
import MembersList from '../../Components/MembersList.vue'

defineProps(['member', 'members'])
</script>

<template>
  <LayoutAuthenticated title="Members">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Members</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- removed overflow-hidden from below div -->
        <div class="bg-white shadow-xl sm:rounded-lg p-6">
          <MembersList :list="members" />
        </div>
      </div>
    </div>
  </LayoutAuthenticated>
</template>
