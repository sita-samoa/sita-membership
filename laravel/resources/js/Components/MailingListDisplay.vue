<script setup>
import { Link } from '@inertiajs/vue3'
import { Button } from 'flowbite-vue'
import CardBox from '@/Components/CardBox.vue'
import CardBoxComponentTitle from '@/Components/CardBoxComponentTitle.vue'

const props = defineProps({
  mailingLists: Object,
})
</script>

<template>
  <CardBox v-if="$page.props.user.permissions.canReadAny" is-hoverable>
    <CardBoxComponentTitle title="Mailing Lists" />
    <Link v-for="(obj, id) in props.mailingLists" :href="`/mailing-lists?id=${id}`" as="button">
      <Button class="p-3 mt-3 mr-3 relative"
        >{{ obj[0] }}&nbsp; <span className="bg-zinc-300 text-blue-500 absolute translate-x-2 -translate-y-2 top-0 right-0 py-0.5 px-2.5 shadow-md rounded-full text-sm">{{ obj[1] }}</span></Button
      >
    </Link>
  </CardBox>
</template>
