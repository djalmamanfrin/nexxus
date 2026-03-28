<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Payment } from '@/types';
import { PencilIcon, Search, Trash2Icon } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';
import FilterText from '@/components/filters/FilterText.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import AppTable from '@/components/base/AppTable.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import AppButton from '@/components/AppButton.vue';
import { router, useForm } from '@inertiajs/vue3';
import EditFields from '@/pages/payments/EditFields.vue';
import EditFile from '@/pages/payments/EditFile.vue';
import SidebarDrawerTab from '@/components/ui/sidebar/SidebarDrawerTab.vue';
import SidebarDrawer from '@/components/ui/sidebar/SidebarDrawer.vue';
import SidebarDrawerTabs from '@/components/ui/sidebar/SidebarDrawerTabs.vue';
import SidebarDrawerPanel from '@/components/ui/sidebar/SidebarDrawerPanel.vue';

const props = defineProps<{
    payments: {
        data: Payment[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    statuses: SelectOption[];
    search_by?: string;
    status?: string | number | null;
}>();

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
        status: props.status || null,
    },
    '/payments',
);

const open = ref(false);
const selectedItem = ref<Expense | null>(null);

const dataForm = useForm({
    expense_id: null,
    bank_account_id: null,
    payment_status_id: null,
    payment_type_id: null,
    amount: null,
    paid_at: null,
});

const fileForm = useForm({
    attachment: null,
});

const handleEdit = (item: Expense) => {
    selectedItem.value = item;

    dataForm.defaults({
        expense_id: item.expense_id,
        bank_account_id: item.bank_account_id,
        payment_status_id: item.payment_status_id,
        payment_type_id: item.payment_type_id,
        amount: item.amount,
        paid_at: item.paid_at,
    });
    dataForm.reset();
    open.value = true;
};

const handleDelete = (item: Expense) => {
    if (!confirm('Tem certeza que deseja excluir esta despesa?')) return;

    router.delete(`/payments/${item.id}`, {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errors) => {
            console.error(errors);
        },
    });
};
const handleSave = () => {
    if (!selectedItem.value) return;
    const id = selectedItem.value.id;

    if (dataForm.isDirty) {
        dataForm.patch(`/payments/${id}`, {
            preserveState: true,
            onSuccess: () => {
                open.value = false;
                dataForm.reset();
            },
        });
    }

    if (fileForm.isDirty) {
        fileForm.post(`/payments/${id}/attachments`, {
            forceFormData: true,
            preserveState: true,
            onSuccess: () => {
                open.value = false;
                fileForm.reset();
            },
        });
    }
};

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Pagamentos',
        href: '',
        btn: { label: 'Novo Pagamento', url: '/payments' },
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="content-box">
            <FlashMessage />
            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filters"
                    @change="search"
                    @clear="clear"
                >
                    <FilterText
                        label="Digite algo referente ao pagamento"
                        name="search_by"
                        placeholder="Ex: cpf, cnpj ou qualquer texto no comprovante"
                        :icon="Search"
                    />
                    <FilterTabs label="Status" name="status" :tabs="statuses" />
                    <!--                    <FilterSelect-->
                    <!--                        :options="statuses"-->
                    <!--                        :selectedValue="status"-->
                    <!--                        label="Status"-->
                    <!--                        name="status"-->
                    <!--                        width="w-56"-->
                    <!--                    />-->
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <AppTable
                :columns="[
                    { key: 'attachments', label: 'Imagem', align: 'left' },
                    { key: 'amount', label: 'Valor', type: 'money' },
                    { key: 'status.name', label: 'Status' },
                    { key: 'paid_at', label: 'Pago em', type: 'datetime' },
                    { key: 'created_at', label: 'Criado em', type: 'datetime' },
                ]"
                :items="props.payments"
            >
                <template #cell-attachments="{ item }">
                    <span v-if="item.attachments?.length">
                        {{ item.attachments[0].original_name }}
                    </span>
                    <span v-else>-</span>
                </template>

                <template #cell-status.name="{ item }">
                    <span
                        :class="[
                            'rounded px-2 py-1 text-xs',
                            `bg-${item.status?.color}-100 text-${item.status?.color}-800`,
                        ]"
                    >
                        {{ item.status?.name || '-' }}
                    </span>
                </template>
                <template #actions="{ item }">
                    <AppButton
                        @click="handleEdit(item)"
                        title="Editar pagamento"
                        variant="link"
                        :icon="PencilIcon"
                    />
                    <AppButton
                        title="Excluir pagamento"
                        variant="link"
                        :href="`/payments/${item.id}`"
                        :icon="Trash2Icon"
                    />
                </template>
            </AppTable>
        </div>
        <SidebarDrawer :open="open" @close="open = false" @save="handleSave">
            <template #title>Comprovante de Pagamento</template>

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
    </AppLayout>
</template>
