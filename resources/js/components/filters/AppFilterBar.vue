<script setup lang="ts">
import { provide, watch, computed, reactive } from 'vue';
import type { FilterMeta } from '@/types/filters';
import { XIcon } from 'lucide-vue-next';
import ActiveFilters from './ActiveFilters.vue';

const props = defineProps({
    filters: Object,
});

const emit = defineEmits(['update:filters', 'change', 'clear']);

const filters = props.filters;

provide('filters', filters);

watch(
    filters,
    () => {
        emit('update:filters', filters);
        emit('change', filters);
    },
    { deep: true },
);

function clearFilters() {
    Object.keys(filters).forEach((key) => {
        if (Array.isArray(filters[key])) {
            filters[key] = [];
        } else {
            filters[key] = null;
        }
    });

    emit('clear');
}

const hasActiveFilters = computed(() => {
    return Object.keys(filtersMeta).length > 0;
});

const filtersMeta = reactive<Record<string, FilterMeta>>({});

function registerFilter(name, config) {
    if (!config) {
        delete filtersMeta[name];
        return;
    }

    filtersMeta[name] = config;
}

provide('registerFilter', registerFilter);
provide('filtersMeta', filtersMeta);
</script>

<template>
    <div class="space-y-3">
        <div class="flex flex-wrap gap-3">
            <slot />
            <div class="flex items-center pt-5">
                <XIcon
                    v-if="hasActiveFilters"
                    class="h-4 w-4 cursor-pointer text-gray-500 hover:text-gray-800"
                    @click="clearFilters"
                />
            </div>
        </div>
        <ActiveFilters v-if="hasActiveFilters" :filters="filters" />
    </div>
</template>
