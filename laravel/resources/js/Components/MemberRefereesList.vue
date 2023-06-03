<script setup>
import { ListGroup, ListGroupItem } from 'flowbite-vue'
import PencilOutlineIcon from 'vue-material-design-icons/PencilOutline.vue'

defineProps(['list'])
</script>
<template>
  <list-group class="w-full mt-5" v-show="list.length">
    <list-group-item v-for="item in list" @click="$emit('editItem', item.id)">
      <template #prefix>
        <PencilOutlineIcon fillColor="green" />
      </template>
      {{ item.name }} | {{ item.organisation }}
    </list-group-item>
  </list-group>
</template>
