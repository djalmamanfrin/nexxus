<script setup>
import { reactive, provide, watch, computed } from 'vue'
import { XIcon } from 'lucide-vue-next';
import ActiveFilters from './ActiveFilters.vue'

const props = defineProps({
    modelValue: Object
})

const emit = defineEmits([
    'update:modelValue',
    'change',
    'clear'
])

const filters = reactive({ ...props.modelValue })

provide('filters', filters)

watch(filters, () => {
    emit('update:modelValue', filters)
    emit('change', filters)
}, { deep: true })

function clearFilters() {
    Object.keys(filters).forEach(key => {
        filters[key] = ''
    })

    emit('clear')
}

/* 👇 verifica se existe algum filtro ativo */
const hasActiveFilters = computed(() => {
    return Object.values(filters).some(value => {
        return value !== null && value !== ''
    })
})
</script>

<template>
    <div class="space-y-3">
        <div class="flex items-center gap-3 flex-wrap">
            <slot />
            <XIcon
                v-if="hasActiveFilters"
                class="w-4 h-4 self-center cursor-pointer text-sm text-gray-500 hover:text-gray-800 shrink-0"
                @click="clearFilters"
            />
        </div>
        <ActiveFilters
            v-if="hasActiveFilters"
            :filters="filters"
        />
    </div>
</template>
