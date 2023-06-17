<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { Alert, Button } from 'flowbite-vue'

import { mdiReload } from '@mdi/js'
import CardBox from '@/Components/CardBox.vue'
import BaseButton from '@/Components/BaseButton.vue'
import CardBoxComponentTitle from '@/Components/CardBoxComponentTitle.vue'

const props = defineProps({
  mailingLists: Object
})

</script>

<template>
    <CardBox v-if="$page.props.user.permissions.canReadAny" is-hoverable>
      <CardBoxComponentTitle title="Mailing Lists" />
      <section className="flex flex-row" v-for="count, title in props.mailingLists">
      <Link as="button">
        <Button class="p-3 mt-3 mr-3">{{ title }} ({{ count }})</Button>
      </Link>
      </section>

    </CardBox>
</template>
