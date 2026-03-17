<script setup>
import { provide, watch, computed, reactive } from 'vue';
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
        filters[key] = '';
    });

    emit('clear');
}

const hasActiveFilters = computed(() => {
    return Object.values(filters).some((value) => {
        return value !== null && value !== '' && value !== undefined;
    });
});

const filtersMeta = reactive({});

function registerFilter(name, config) {
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
        <ActiveFilters
            v-if="hasActiveFilters"
            :filters="filters"
        />
    </div>
</template>
