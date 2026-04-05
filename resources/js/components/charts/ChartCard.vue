<script setup lang="ts">
import AppTooltip from '@/components/base/AppTooltip.vue';
import AppLabel from '@/components/base/AppLabel.vue';

defineProps<{
    title: string;
    icon?: string;
    tooltip?: string;
}>();
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="mb-4 flex items-center justify-between gap-2">
            <AppLabel :label="title" />
            <AppTooltip :icon="icon" :tooltip="tooltip" />
        </div>

        <slot />
    </div>
</template>
