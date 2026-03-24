<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import { formatDate, formatDateTime } from '@/lib/date.ts';
import { formatMoney } from '@/lib/money.ts';
import { Pencil, Trash } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';
import SidebarDrawer from '@/components/ui/sidebar/SidebarDrawer.vue';
import SidebarDrawerTabs from '@/components/ui/sidebar/SidebarDrawerTabs.vue';
import SidebarDrawerTab from '@/components/ui/sidebar/SidebarDrawerTab.vue';
import SidebarDrawerPanel from '@/components/ui/sidebar/SidebarDrawerPanel.vue';
import EditFields from '@/pages/payments/EditFields.vue';
import EditFile from '@/pages/payments/EditFile.vue';

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

const dataForm = useForm({
    expense_id: '',
    bank_account_id: '',
    payment_status_id: '',
    payment_type_id: '',
    amount: '',
    paid_at: '',
});

const fileForm = useForm({
    attachment: null,
});
const handleSave = () => {
    if (!selectedItem.value?.id) return;
    const paymentId = selectedItem.value.id;

    console.log('Iniciando save...');

    if (dataForm.isDirty) {
        dataForm.patch(`/payments/${paymentId}`, {
            preserveState: true,
            onStart: () => {
                console.log('Iniciando save...');
            },
            onSuccess: () => {
                console.log('Salvo com sucesso');
            },
            onError: (errors) => {
                console.error('Erro ao salvar:', errors);
            },
            onFinish: () => {
                console.log('Finalizou requisição');
            },
        });
    }

    if (fileForm.isDirty) {
        fileForm.post(`/payments/${paymentId}/attachments`, {
            forceFormData: true,
            preserveState: true,
            onStart: () => {
                console.log('Iniciando save...');
            },
            onSuccess: () => {
                console.log('Salvo com sucesso');
                fileForm.attachment = null;
            },
            onError: (errors) => {
                console.error('Erro ao salvar:', errors);
            },
            onFinish: () => {
                console.log('Finalizou requisição');
            },
        });
    }

    open.value = false;
    dataForm.reset();
    fileForm.reset();
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
                        :colspan="columns.length"
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
    <SidebarDrawer :open="open" @close="open = false" @save="handleSave">
        <template #title> Comprovante de Pagamento </template>

        <SidebarDrawerTabs>
            <SidebarDrawerTab name="info" label="Informações" />
            <SidebarDrawerTab name="arquivo" label="Arquivo" />
        </SidebarDrawerTabs>

        <SidebarDrawerPanel name="info">
            <EditFields :payment="selectedItem" :form="dataForm" />
        </SidebarDrawerPanel>
        <SidebarDrawerPanel name="arquivo">
            <EditFile :payment="selectedItem" :form="fileForm" />
        </SidebarDrawerPanel>
    </SidebarDrawer>
</template>
