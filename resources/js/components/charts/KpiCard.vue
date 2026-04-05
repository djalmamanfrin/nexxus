<script setup lang="ts">
import AppTooltip from '@/components/base/AppTooltip.vue';
import AppLabel from '@/components/base/AppLabel.vue';

defineProps<{
    title: string;
    value: string | number;
    tooltip?: string;
}>();
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >
        <!-- HEADER -->
        <div class="flex items-center justify-between gap-2">
            <AppLabel :label="title" />
            <AppTooltip icon="info" :tooltip="tooltip" />
        </div>

        <h2 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
            {{ value }}
        </h2>
    </div>
</template>
