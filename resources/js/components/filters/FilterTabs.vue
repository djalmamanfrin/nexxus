<script setup lang="ts">
import { inject, computed, onMounted } from 'vue';
import { SelectOption } from '@/types/select';
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

function selectTab(value: string | number) {
    filters[props.name] = value;

    const selected = props.tabs.find((t) => t.value === value);

    registerFilter?.(props.name, {
        label: 'com status',
        type: 'tab',
        value: selected?.value,
        display: selected?.label,
    });
}

onMounted(() => {
    const selected = props.tabs.find(
        (item) => item.value === Number(filters[props.name]),
    );
    if (!selected) return;

    registerFilter?.(props.name, {
        label: 'com status',
        type: 'tab',
        value: selected.value,
        display: selected.label,
    });
});
</script>

<template>
    <div class="flex flex-col gap-1">
        <AppLabel :label="label" />
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
