<script setup>
import { useForm, Head, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import LayoutGuest from "@/Layouts/LayoutGuest.vue";
import SectionFullScreen from "@/Components/SectionFullScreen.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import BaseButton from "@/Components/BaseButton.vue";
import FormValidationErrors from "@/Components/FormValidationErrors.vue";
import NotificationBarInCard from "@/Components/NotificationBarInCard.vue";
import BaseLevel from "@/Components/BaseLevel.vue";
import ApplicationMark from '@/Components/ApplicationMark.vue'

const props = defineProps({
  status: {
    type: String,
    default: null,
  },
});

const form = useForm({});

const verificationLinkSent = computed(
  () => props.status === "verification-link-sent"
);

const submit = () => {
  form.post(route("verification.send"));
};
</script>

<template>
  <LayoutGuest>
    <Head title="Email Verification" />

    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" is-form @submit.prevent="submit">
        <FormValidationErrors />

        <NotificationBarInCard v-if="verificationLinkSent" color="info">
          A new verification link has been sent to the email address you
          provided during registration.
        </NotificationBarInCard>

        <ApplicationMark class="block h-14 w-auto mb-6" />

        <FormField>
          <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
          </div>
        </FormField>

        <BaseDivider />

        <BaseLevel>
          <BaseButton
            type="submit"
            color="info"
            label="Resend Verification Email"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          />
          <div>
            <Link :href="route('profile.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"> Edit Profile</Link>

            <Link :href="route('logout')" method="post" as="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-2"> Log Out </Link>
          </div>
        </BaseLevel>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
