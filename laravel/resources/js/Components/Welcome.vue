<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { Alert, Button } from 'flowbite-vue';

</script>

<template>
    <div>
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
    </div>
</template>
