<script setup>
import { provide, watch, computed } from 'vue'
import { XIcon } from 'lucide-vue-next';
import ActiveFilters from './ActiveFilters.vue'

const props = defineProps({
    filters: Object
})

const emit = defineEmits([
    'update:filters',
    'change',
    'clear'
])

const filters = props.filters

provide('filters', filters)

watch(filters, () => {
    emit('update:filters', filters)
    emit('change', filters)
}, { deep: true })

function clearFilters() {
    Object.keys(filters).forEach(key => {
        filters[key] = ''
    })

    emit('clear')
}

const hasActiveFilters = computed(() => {
    return Object.values(filters).some(value => {
        return value !== null && value !== '' && value !== undefined
    })
})
</script>

<template>
    <div class="space-y-3">
        <div class="flex gap-3 flex-wrap">
            <slot />
            <div class="flex items-center pt-5">
                <XIcon
                    v-if="hasActiveFilters"
                    class="w-4 h-4 cursor-pointer text-gray-500 hover:text-gray-800"
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
