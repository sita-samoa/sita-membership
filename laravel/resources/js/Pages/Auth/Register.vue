<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3'
import { onMounted, onUnmounted } from 'vue'
import { mdiAccount, mdiEmail, mdiFormTextboxPassword } from '@mdi/js'
import LayoutGuest from '@/Layouts/LayoutGuest.vue'
import SectionFullScreen from '@/Components/SectionFullScreen.vue'
import CardBox from '@/Components/CardBox.vue'
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import BaseDivider from '@/Components/BaseDivider.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseLevel from '@/Components/BaseLevel.vue'
import FormValidationErrors from '@/Components/FormValidationErrors.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { useReCaptcha } from 'vue-recaptcha-v3'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
  captcha_token: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}

const { executeRecaptcha, recaptchaLoaded, instance } = useReCaptcha()
const recaptcha = async () => {
  await recaptchaLoaded()
  form.captcha_token = await executeRecaptcha('register')
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
</script>

<template>
  <LayoutGuest>
    <Head title="Register" />

    <SectionFullScreen v-slot="{ cardClass }" bg="purplePink">
      <CardBox :class="cardClass" class="my-24" is-form @submit.prevent="recaptcha">
        <FormValidationErrors />

        <AuthenticationCardLogo />

        <FormField label="Name" label-for="name" help="Please enter your name">
          <FormControl id="name" v-model="form.name" :icon="mdiAccount" autocomplete="name" type="text" required />
        </FormField>

        <FormField label="Email" label-for="email" help="Please enter your email">
          <FormControl id="email" v-model="form.email" :icon="mdiEmail" autocomplete="email" type="email" required />
        </FormField>

        <FormField label="Password" label-for="password" help="Please enter new password">
          <FormControl id="password" v-model="form.password" :icon="mdiFormTextboxPassword" type="password" autocomplete="new-password" required />
        </FormField>

        <FormField label="Confirm Password" label-for="password_confirmation" help="Please confirm your password">
          <FormControl id="password_confirmation" v-model="form.password_confirmation" :icon="mdiFormTextboxPassword" type="password" autocomplete="new-password" required />
        </FormField>

        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
          <InputLabel for="terms">
            <span class="pb-3">Terms and Conditions</span>
            <div class="flex items-center mt-2">
              <Checkbox id="terms" v-model:checked="form.terms" class="text-primary" name="terms" required />

              <div class="ml-2 text-xs"><strong>Applicants Declaration:</strong> I declare that all information is true and correct, and if admitted to the Association, I understand that I am bound to the Rules, regulations and Codes of the Association as amended from time to time.</div>
            </div>
            <InputError class="mt-2" :message="form.errors.terms" />
          </InputLabel>
        </div>

        <BaseDivider />

        <BaseLevel>
          <BaseButton type="submit" color="info" label="Register" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" />
          <Link :href="route('login')" class="text-sm"> Have an account? </Link>
        </BaseLevel>
      </CardBox>
    </SectionFullScreen>
  </LayoutGuest>
</template>
