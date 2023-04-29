<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MembersList from '../../Components/MembersList.vue';

defineProps([
  'member',
  'members',
]);

</script>

<template>
    <AppLayout title="Summary">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Members
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <MembersList :list="members" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
