<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SignupSteps from '../../Components/SignupSteps.vue';

defineProps([
  'member',
  'options',
  'qualifications'
]);

</script>

<template>
    <AppLayout title="Sign Up">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sign Up
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <SignupSteps
                        :member="member"
                        :options="options"
                        :qualifications="qualifications"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
