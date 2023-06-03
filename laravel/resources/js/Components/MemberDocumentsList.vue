<script setup>
import { ListGroup, ListGroupItem } from 'flowbite-vue'
import PencilOutlineIcon from 'vue-material-design-icons/PencilOutline.vue'
import CloudDownloadIcon from 'vue-material-design-icons/CloudDownload.vue'

defineProps(['list'])

function bytesToSize(bytes) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
  if (bytes == 0) return '0 Byte'
  var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)))
  return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i]
}
</script>
<template>
  <list-group v-show="list.length" class="w-full">
    <list-group-item v-for="item in list" @click="$emit('editItem', item.id)">
      <template #prefix>
        <PencilOutlineIcon fill-color="green" />
      </template>
      {{ item.title }} <span v-show="!item.title">{{ item.file_name }}&nbsp;</span> ({{ bytesToSize(item.file_size) }})
      <template #suffix>
        <div title="Download" class="place-items-end" @click.stop="$emit('download', item.id)">
          <CloudDownloadIcon fill-color="currentColor" />
        </div>
      </template>
    </list-group-item>
  </list-group>
</template>
