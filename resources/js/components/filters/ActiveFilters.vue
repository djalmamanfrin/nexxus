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
    if (meta.type === 'tab') {
        return normalize(meta.display ? meta.display : value);
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
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <!-- Label inicial -->
        <span
            class="rounded bg-gray-200 px-2 py-1 text-gray-600 dark:bg-gray-800 dark:text-gray-300"
        >
            Os resultados:
        </span>

        <!-- Filtros ativos -->
        <template v-for="(value, key) in filters" :key="key">
            <span
                v-if="value"
                class="inline-flex items-center gap-1 rounded bg-gray-200 px-2 py-1 dark:bg-gray-800"
            >
                <span class="text-gray-500 dark:text-gray-400">
                    {{ getLabel(key) }}:
                </span>

                <span class="font-medium text-gray-700 dark:text-gray-200">
                    {{ getValueLabel(key, value) }}
                </span>
            </span>
        </template>
    </div>
</template>
