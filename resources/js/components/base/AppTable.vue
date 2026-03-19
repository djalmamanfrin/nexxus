<script setup>
import Pagination from '@/components/Pagination.vue';
import { useSlots } from 'vue';
import { formatDate, formatDateTime } from '@/lib/date.ts';
import { formatMoney } from '@/lib/money.ts';

const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    items: {
        type: Object,
        required: true,
    },
});

const slots = useSlots();
const hasActions = !!slots.actions;

// =======================
// FORMATADORES (SÓ TEXTO)
// =======================
const formatValue = (item, column) => {
    const value = item[column.key];

    switch (column.type) {
        case 'money':
            return formatMoney(value);

        case 'date':
            return value ? formatDate(value) : '-';

        case 'datetime':
            return value ? formatDateTime(value) : '-';

        case 'boolean':
            return value ? 'Sim' : 'Não';

        case 'payment_status':
            // Aqui só texto (sem UI)
            return getPaymentStatusLabel(value);

        default:
            return value ?? '-';
    }
};

// =======================
// DOMÍNIO (STATUS)
// =======================
const getPaymentStatusLabel = (value) => {
    const map = {
        1: 'Pendente',
        2: 'Pago',
        3: 'Cancelado',
    };

    return map[value] ?? '-';
};
</script>

<template>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr class="table-header">
                    <th
                        v-for="column in columns"
                        :key="column.key"
                        class="table-row-header"
                    >
                        {{ column.label }}
                    </th>

                    <th v-if="hasActions" class="table-row-header">Ações</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="item in items.data"
                    :key="item.id"
                    class="table-body"
                >
                    <td
                        v-for="column in columns"
                        :key="column.key"
                        class="table-row-body"
                    >
                        <!-- SLOT TEM PRIORIDADE -->
                        <slot :name="`cell-${column.key}`" :item="item">
                            {{ formatValue(item, column) }}
                        </slot>
                    </td>

                    <!-- AÇÕES FIXAS -->
                    <td v-if="hasActions" class="table-actions">
                        <div class="table-actions-align gap-2">
                            <slot name="actions" :item="item" />
                        </div>
                    </td>
                </tr>

                <!-- EMPTY STATE -->
                <tr v-if="!items.data?.length">
                    <td
                        :colspan="columns.length + (hasActions ? 1 : 0)"
                        class="py-4 text-center text-gray-500"
                    >
                        Nenhum registro encontrado
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- PAGINAÇÃO FIXA -->
        <Pagination v-if="items.links" :links="items.links" />
    </div>
</template>
