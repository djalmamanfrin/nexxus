<script setup lang="ts">
import { type BreadcrumbItem, Payment } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import EditFields from '@/pages/payments/EditFields.vue';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFileInput from '@/components/base/AppFileInput.vue';

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

const columns = [
    { key: 'attachments', label: 'Imagem', align: 'left' },
    { key: 'amount', label: 'Valor', type: 'money' },
    { key: 'status.name', label: 'Status' },
    { key: 'paid_at', label: 'Pago em', type: 'datetime' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Pagamentos',
        href: '',
        btn: { label: 'Novo Pagamento', url: '/payments' },
    },
];
</script>

<template>
    <CrudIndexPage
        :items="props.payments"
        :columns="columns"
        base-url="/payments"
        :breadcrumbs="breadcrumbItems"
        v-model:filters="filters"
        :search="search"
        :clear="clear"
        :initialForm="{
            expense_id: null,
            bank_account_id: null,
            payment_status_id: null,
            payment_type_id: null,
            amount: null,
            paid_at: null,
        }"
        :mapToForm="
            (item) => ({
                expense_id: item.expense_id ?? null,
                bank_account_id: item.bank_account_id ?? null,
                amount: item.amount,
                paid_at: item.paid_at,
            })
        "
    >
        <template #filters>
            <FilterText
                label="Buscar pagamneto"
                name="search_by"
                placeholder="CPF, CNPJ ou texto"
            />
            <FilterTabs label="Status" name="status" :tabs="statuses" />

            <!--                    <FilterSelect-->
            <!--                        :options="statuses"-->
            <!--                        :selectedValue="status"-->
            <!--                        label="Status"-->
            <!--                        name="status"-->
            <!--                        width="w-56"-->
            <!--                    />-->
        </template>

        <template #form="{ item, form }">
            <EditFields :payment="item" :form="form" />
        </template>

        <template #file="{ item, form }">
            <AppFileInput :attachments="item?.attachments" :form="form" />
        </template>
    </CrudIndexPage>
</template>
