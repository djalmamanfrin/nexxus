<script setup>
import { formatDateShort} from "@/lib/date.js";

const props = defineProps({
    filters: Object
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
        <template
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
        </template>
    </div>
</template>
