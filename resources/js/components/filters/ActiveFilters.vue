<script setup lang="ts">
import { inject } from 'vue';
import type { FilterMeta } from '@/types/filters';

defineProps({
    labels: Object,
    filters: Object,
});

const filtersMeta = inject<Record<string, FilterMeta>>('filtersMeta', {});

function getLabel(key) {
    return filtersMeta[key]?.label || key;
}

function getValueLabel(key, value) {
    const meta = filtersMeta[key];

    if (!meta) return normalize(value);

    if (meta.format) {
        return normalize(meta.format(value));
    }

    return normalize(value);
}

function normalize(value) {
    if (Array.isArray(value)) {
        return value.filter(Boolean).join(', ');
    }

    if (typeof value === 'string') {
        return value.toLowerCase();
    }

    return value;
}

function removeFilter(key) {
    const meta = filtersMeta[key];
    if (meta?.clear) {
        meta.clear();
        return;
    }
    filters[key] = null;
}
</script>

<template>
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <span class="rounded bg-gray-200 px-2 py-1 text-gray-600">
            Os resultados:
        </span>

        <template v-for="(meta, key) in filtersMeta" :key="key">
            <span
                @click="removeFilter(key)"
                class="inline-flex items-center gap-1 rounded bg-gray-200 px-2 py-1"
            >
                <span class="text-gray-500"> {{ meta.label }}: </span>

                <span class="font-medium text-gray-700">
                    {{ meta.format ? meta.format() : '' }}
                </span>
            </span>
        </template>
    </div>
</template>
