<script setup lang="ts">
import { SelectOption } from '@/types/select';
import { computed, inject, watchEffect } from 'vue';
import AppLabel from '@/components/base/AppLabel.vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps<{
    label: string;
    name: string;
    tabs: SelectOption[];
}>();

const currentValue = computed(() => filters[props.name] ?? '');

function isActive(value: string | number) {
    return String(currentValue.value) === String(value);
}

const normalizedTabs = computed(() => {
    const hasAll = props.tabs.some((t) => t.value === '');
    if (hasAll) return props.tabs;

    return [{ value: '', label: 'Todos' }, ...props.tabs];
});

watchEffect(() => {
    const value = filters[props.name];

    if (!value) {
        registerFilter?.(props.name, null);
        return;
    }

    const selected = props.tabs.find((t) => String(t.value) === String(value));

    if (!selected) {
        registerFilter?.(props.name, null);
        return;
    }

    registerFilter?.(props.name, {
        label: 'com status',
        format: () => selected.label?.toLowerCase(),
        clear: () => {
            filters[props.name] = '';
        },
    });
});

function selectTab(value: string | number) {
    filters[props.name] = value;
}
</script>

<template>
    <div class="flex flex-col gap-1">
        <AppLabel v-if="label" :label="label" />
        <div
            class="inline-flex gap-1 rounded-lg bg-neutral-100 dark:bg-neutral-800"
        >
            <button
                v-for="{ value, label } in normalizedTabs"
                :key="value"
                @click="selectTab(value)"
                :class="[
                    'flex items-center rounded-md px-3.5 py-2.5 transition-colors',
                    isActive(value)
                        ? 'shadow-xs bg-white dark:bg-neutral-700 dark:text-neutral-100'
                        : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
                ]"
            >
                <span class="ml-1.5 text-sm">{{ label }}</span>
            </button>
        </div>
    </div>
</template>
