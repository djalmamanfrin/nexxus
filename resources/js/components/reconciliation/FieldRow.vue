<script setup lang="ts">
import AppBadge from '@/components/table/AppBadge.vue';

defineProps<{
    label: string;
    value?: string;
    color?: string;
    mono?: boolean;
}>();
</script>

<template>
    <div class="flex items-center justify-between">
        <span class="text-gray-500">{{ label }}</span>

        <AppBadge v-if="color && value" :label="value" :color="color" />
        <span v-else
            :class="[
                'font-medium text-gray-800 dark:text-gray-200',
                mono ? 'font-mono text-gray-700 dark:text-gray-300' : '',
            ]"
        >
            {{ value ?? '---' }}
        </span>
    </div>
</template>
