<script setup>
import { FwbListGroup, FwbListGroupItem } from 'flowbite-vue'
import PencilOutlineIcon from 'vue-material-design-icons/PencilOutline.vue'

defineProps(['list'])
</script>
<template>
  <fwb-list-group v-show="list.length" class="w-full">
    <fwb-list-group-item v-for="item in list" @click="$emit('editItem', item.id)">
      <template #prefix>
        <PencilOutlineIcon fill-color="green" />
      </template>
      {{ item.qualification }}
    </fwb-list-group-item>
  </fwb-list-group>
</template>
