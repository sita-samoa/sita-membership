<script setup>
import { ListGroup, ListGroupItem } from 'flowbite-vue'
import PencilOutlineIcon from 'vue-material-design-icons/PencilOutline.vue'

defineProps(["list"])

function bytesToSize(bytes) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
  if (bytes == 0) return '0 Byte'
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)))
  return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i]
}
</script>
<template>
<list-group class="w-full" v-show="list.length">
  <list-group-item v-for="item in list" @click="$emit('editItem', item.id)">
    <template #prefix>
      <PencilOutlineIcon fillColor="green" />
    </template>
    {{ item.title }} <span v-show="!item.title">{{ item.file_name }}&nbsp;</span> ({{ bytesToSize(item.file_size) }})
    <template #suffix>
      <span @click.stop="$emit('download', item.id)">
        <svg class="w-4 h-4 fill-current" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z" clip-rule="evenodd"></path></svg>
      </span>
    </template>
  </list-group-item>
</list-group>
</template>
