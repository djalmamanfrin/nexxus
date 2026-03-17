<script setup lang="ts">
import { inject } from 'vue';

const props = defineProps({
    labels: Object,
    filters: Object,
});

const filtersMeta = inject('filtersMeta', {});

function getLabel(key) {
    return filtersMeta[key]?.label || key;
}

function getValueLabel(key, value) {
    const meta = filtersMeta[key];

    if (!meta) return normalize(value);

    // 🔥 prioridade: format
    if (meta.format) {
        return normalize(meta.format(value));
    }

    // traduz select
    if (meta.type === 'select' && meta.options) {
        const found = meta.options.find((opt) => opt.value == value);
        return normalize(found ? found.label : value);
    }

    return normalize(value);
}

function normalize(value) {
    if (typeof value === 'string') {
        return value.toLowerCase();
    }

    return value;
}
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <span class="rounded bg-gray-200 px-2 py-1 text-xs">
            <span class="text-gray-500"> Os resultados: </span>
        </span>
        <div v-for="(value, key) in filters" :key="key">
            <span v-if="value" class="rounded bg-gray-200 px-2 py-1 text-xs">
                <span class="text-gray-500"> {{ getLabel(key) }}: </span>
                <span class="font-semibold tracking-wide">
                    {{ getValueLabel(key, value) }}
                </span>
            </span>
        </div>
    </div>
</template>
