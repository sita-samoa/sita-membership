<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'
import { mdiAccount, mdiAsterisk } from '@mdi/js'
import LayoutGuest from '@/Layouts/LayoutGuest.vue'
import SectionFullScreen from '@/Components/SectionFullScreen.vue'
import CardBox from '@/Components/CardBox.vue'
import FormCheckRadioGroup from '@/Components/FormCheckRadioGroup.vue'
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'
import FormValidationErrors from '@/Components/FormValidationErrors.vue'
import NotificationBarInCard from '@/Components/NotificationBarInCard.vue'
import BaseLevel from '@/Components/BaseLevel.vue'
import { useReCaptcha } from 'vue-recaptcha-v3'
import { onMounted, onUnmounted } from 'vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'

const { executeRecaptcha, recaptchaLoaded, instance } = useReCaptcha()
const recaptcha = async () => {
  // This variable is setup from node not Laravel .env file
  //  when npm run build is run.
  if (process.env.NODE_ENV === 'production') {
    await recaptchaLoaded() 
    form.captcha_token = await executeRecaptcha('login')
  }
  submit()
}

onMounted(async () => {
  await recaptchaLoaded()
  if (instance.value) {
    instance.value.showBadge()
  }
})

onUnmounted(() => {
  if (instance.value) {
    instance.value.hideBadge()
  }
})

const props = defineProps({
  canResetPassword: Boolean,
  status: {
    type: String,
    default: null,
  },
})

const form = useForm({
  email: '',
  password: '',
  remember: [],
  captcha_token: '',
})

const submit = () => {
  form
    .transform(data => ({
      ...data,
      remember: form.remember && form.remember.length ? 'on' : '',
    }))
    .post(route('login'), {
      onFinish: () => form.reset('password'),
    })
}
</script>

<template>
  <LayoutGuest>
    <Head title="Login" />

    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" is-form @submit.prevent="recaptcha">
        <FormValidationErrors />

        <NotificationBarInCard v-if="status" color="info">
          {{ status }}
        </NotificationBarInCard>

        <AuthenticationCardLogo />

        <FormField label="Email" label-for="email" help="Please enter your email">
          <FormControl id="email" v-model="form.email" :icon="mdiAccount" autocomplete="email" type="email" required />
        </FormField>

        <FormField label="Password" label-for="password" help="Please enter your password">
          <FormControl id="password" v-model="form.password" :icon="mdiAsterisk" type="password" autocomplete="current-password" required />
        </FormField>

        <BaseLevel>
          <FormCheckRadioGroup v-model="form.remember" name="remember" :options="{ remember: 'Remember' }" />
          <Link :href="route('password.request')" class="text-sm"> Forgot password? </Link>
        </BaseLevel>

        <BaseDivider />

        <BaseLevel>
          <BaseButtons>
            <BaseButton type="submit" color="info" label="Login" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
            <BaseButton route-name="register" color="info" outline label="Create an account" />
          </BaseButtons>
        </BaseLevel>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
