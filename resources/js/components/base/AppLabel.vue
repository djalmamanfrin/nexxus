<script setup>
const props = defineProps({
    label: String,
});
</script>

<template>
    <label
        v-if="label"
        class="text-sm font-medium text-neutral-900 dark:text-neutral-100"
    >
        {{ label }}
    </label>
</template>
