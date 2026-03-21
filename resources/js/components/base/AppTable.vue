<script setup>
import Pagination from '@/components/Pagination.vue';
import { formatDate, formatDateTime } from '@/lib/date.ts';
import { formatMoney } from '@/lib/money.ts';
import { Pencil, Trash } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';

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

        default:
            return value ?? '-';
    }
};

const getAlignClass = (align) => {
    switch (align) {
        case 'left':
            return 'text-left';
        case 'right':
            return 'text-right';
        default:
            return 'text-center';
    }
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
                        :class="getAlignClass(column.align)"
                    >
                        {{ column.label }}
                    </th>
                    <th class="table-row-header text-center">Ações</th>
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
                        :class="getAlignClass(column.align)"
                    >
                        <slot :name="`cell-${column.key}`" :item="item">
                            {{ formatValue(item, column) }}
                        </slot>
                    </td>
                    <td class="table-actions table-actions-align">
                        <AppButton
                            title="Editar pagamento"
                            variant="link"
                            :href="`/payments/${item.id}/edit`"
                            :icon="Pencil"
                        />
                        <AppButton
                            title="Excluir pagamento"
                            variant="link"
                            :href="`/payments/${item.id}`"
                            :icon="Trash"
                        />
                    </td>
                </tr>
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
