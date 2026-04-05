<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >
        <h3 class="mb-4 text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ title }}
        </h3>
        <slot />
    </div>
</template>
