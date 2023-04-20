<script setup>
import { ListGroup, ListGroupItem } from 'flowbite-vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue';

defineProps(["list"])
</script>
<template>
<list-group class="w-full">
  <list-group-item v-for="item in list" @click="$emit('editItem', item.id)">
    <template #prefix>
      <CheckCircleOutlineIcon fillColor="green" />
    </template>
    {{ item.qualification }}
  </list-group-item>
</list-group>
</template>
