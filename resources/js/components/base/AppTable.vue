<script setup>
import { ref } from 'vue';
import Pagination from '@/components/Pagination.vue';
import { formatDate, formatDateTime } from '@/lib/date.ts';
import { formatMoney } from '@/lib/money.ts';
import { Pencil, Trash } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';
import SidebarDrawer from '@/components/ui/sidebar/SidebarDrawer.vue';
import { SidebarHeader } from '@/components/ui/sidebar/index.ts';
import SidebarDrawerTabs from '@/components/ui/sidebar/SidebarDrawerTabs.vue';
import SidebarDrawerTab from '@/components/ui/sidebar/SidebarDrawerTab.vue';
import SidebarDrawerPanel from '@/components/ui/sidebar/SidebarDrawerPanel.vue';

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

const open = ref(false);
const selectedItem = ref(null);

const handleView = (item) => {
    selectedItem.value = item;
    open.value = true;
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
                            @click="handleView(item)"
                            title="Editar pagamento"
                            variant="link"
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
    <SidebarDrawer :open="open" @close="open = false">
        <template #title> Comprovante de Pagamento </template>

        <SidebarDrawerTabs>
            <SidebarDrawerTab name="arquivo" label="Arquivo" />
            <SidebarDrawerTab name="info" label="Informações" />
        </SidebarDrawerTabs>

        <SidebarDrawerPanel name="arquivo"> Aqui vai o arquivo </SidebarDrawerPanel>
        <SidebarDrawerPanel name="info"> Aqui vai as informações </SidebarDrawerPanel>
    </SidebarDrawer>
</template>
