<script setup lang="ts">
import AppTooltip from '@/components/base/AppTooltip.vue';
import AppLabel from '@/components/base/AppLabel.vue';

defineProps<{
    title: string;
    tooltip?: string;
    icon?: string;
    items: {
        label: string;
        value: number; // percentual (0–100)
        color: string; // ex: bg-blue-500
    }[];
}>();
</script>

<template>
    <div
        class="card rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
    >
        <div class="flex items-center justify-between gap-2 mb-4">
            <AppLabel :label="title" />
            <AppTooltip :icon="icon" :tooltip="tooltip" />
        </div>

        <div class="space-y-3">
            <div v-for="(item, index) in items" :key="index">
                <div class="mb-1 flex justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ item.label }}
                    </span>
                    <span class="font-medium">
                        {{ item.value.toFixed(1) }}%
                    </span>
                </div>

                <div
                    class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700"
                >
                    <div
                        class="h-2 rounded-full transition-all"
                        :class="item.color"
                        :style="{ width: item.value + '%' }"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="my-4"></div>
</template>
