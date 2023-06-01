<script setup>
import { ListGroup, ListGroupItem } from 'flowbite-vue'
import PencilOutlineIcon from 'vue-material-design-icons/PencilOutline.vue'

defineProps(['list'])
</script>
<template>
  <list-group v-show="list.length" class="w-full mt-5">
    <list-group-item v-for="item in list" @click="$emit('editItem', item.id)">
      <template #prefix>
        <PencilOutlineIcon fill-color="green" />
      </template>
      {{ item.name }} | {{ item.organisation }}
    </list-group-item>
  </list-group>
</template>
