<script setup>
import { formatDateShort} from "@/lib/date.js";
import {computed} from "vue";

const props = defineProps({
    filters: Object
})

const activeFiltersCount = computed(() => {
    return Object.values(props.filters).filter(value => value !== null && value !== '' && value !== undefined).length
})

const labels = {
    search_by: 'Deve conter',
    status: 'Com status',
    paid_at: 'Pago em',
    created_at: 'Criado até'
}
const values = {
    status: {
        1: 'Pago',
        2: 'Pendente',
    },
}
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <span
            v-if="activeFiltersCount > 0"
            class="px-2 py-1 text-xs bg-gray-200 rounded"
        >
            <span class="text-gray-500">
                Os resultados:
            </span>
        </span>
        <div
            v-for="(value, key) in filters"
            :key="key"
        >
            <span
              v-if="value"
              class="px-2 py-1 text-xs bg-gray-200 rounded"
            >
                <span class="text-gray-500">
                    {{ labels[key] ?? key }}:
                </span>
                 <span class="font-semibold tracking-wide">
                     {{
                         values[key]?.[value] ??
                         (key.includes('_at') ? formatDateShort(value) : value)
                     }}
                </span>
            </span>
        </div>
    </div>
</template>
