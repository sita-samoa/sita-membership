<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { Alert, Button } from 'flowbite-vue';

defineProps({
    totalSubmitted: {
        type: Number,
        default: 0,
    },
    totalLapsed: {
        type: Number,
        default: 0,
    },
    totalOwing: {
        type: Number,
        default: 0,
    },
})

</script>

<template>
    <!-- welcome and sign up -->
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

        <h1 class="mt-8 text-2xl font-medium text-gray-900">
            Welcome
        </h1>

        <Alert type="info" class="mb-2 mt-3">
            Click the Sign up button to begin.
        </Alert>

        <Link href="/members/signup">
            <Button class="p-3 mt-3">Sign Up</Button>
        </Link>
    </div>
    <!-- exec dash -->
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

        <h1 class="mt-8 text-2xl font-medium text-gray-900">
            Executive Dashboard
        </h1>

        <Link href="/members?membership_status_id=2" as="button">
            <Button class="p-3 mt-3 mr-3">Pending Endorsements ({{ totalSubmitted }})</Button>
        </Link>

        <Link href="/members?membership_status_id=5" as="button">
            <Button class="p-3 mt-3">Lapsed Membership ({{ totalLapsed }})</Button>
        </Link>

        <p>Total Owning: {{ totalOwing }}</p>
    </div>
</template>
