<script setup>
import { ref } from "vue";

const props = defineProps({
    id: String,
    type: String,
    placeholder: String,
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

function focus(){
    input.value.focus();
}

defineExpose({
    focus
});
</script>

<template>
    <input
        :id="id"
        ref="input"
        :type="type"
        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-2"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
