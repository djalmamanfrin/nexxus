<script setup lang="ts">
defineProps<{
    label: string;
    value?: string | number | null;
    mono?: boolean;
}>();
</script>

<template>
    <div class="flex items-center justify-between">
        <span class="text-gray-500">{{ label }}</span>

        <span
            :class="[
                'font-medium text-gray-800 dark:text-gray-200',
                mono ? 'font-mono text-gray-700 dark:text-gray-300' : '',
            ]"
        >
            {{ value ?? '---' }}
        </span>
    </div>
</template>
