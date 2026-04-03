<script setup>
const props = defineProps({
    label: String,
    required: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <label
        v-if="label"
        class="text-sm font-medium text-neutral-900 dark:text-neutral-100"
    >
        {{ label }}
        <span v-if="required" class="ml-0.5 text-red-500">*</span>
    </label>
</template>
