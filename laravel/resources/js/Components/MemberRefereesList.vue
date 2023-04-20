<script setup>
import { ListGroup, ListGroupItem } from 'flowbite-vue'
import CheckCircleOutlineIcon from 'vue-material-design-icons/CheckCircleOutline.vue';

defineProps(["list"])
</script>
<template>
<list-group class="w-full mt-5">
  <list-group-item v-for="referee in list" >
    <template #prefix>
      <CheckCircleOutlineIcon fillColor="green" />
    </template>
    {{ referee.name }} | {{ referee.organisation }} | {{ referee.email }} (+685 {{ referee.phone }})
  </list-group-item>
</list-group>
</template>