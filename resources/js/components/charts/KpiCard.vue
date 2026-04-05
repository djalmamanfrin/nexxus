<script setup lang="ts">
defineProps<{
    title: string;
    value: string | number;
}>();
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ title }}
        </p>

        <h2 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
            {{ value }}
        </h2>
    </div>
</template>
