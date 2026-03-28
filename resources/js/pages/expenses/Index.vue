<script setup lang="ts">
import { type BreadcrumbItem, Expense } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import EditFields from '@/pages/expenses/EditFields.vue';
import EditFile from '@/pages/expenses/EditFile.vue';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';

const props = defineProps<{
    expenses: {
        data: Expense[];
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
    '/expenses',
);

const columns = [
    { key: 'attachments', label: 'Imagem', align: 'left' },
    { key: 'amount', label: 'Valor', type: 'money' },
    { key: 'status.name', label: 'Status' },
    { key: 'cost_center.code', label: 'C. de Custo' },
    { key: 'due_at', label: 'Vencimento', type: 'date' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Despesas',
        href: '',
        btn: { label: 'Nova Despesa', url: '/expenses' },
    },
];
</script>

<template>
    <CrudIndexPage
        :items="props.expenses"
        :columns="columns"
        base-url="/expenses"
        :breadcrumbs="breadcrumbItems"
        v-model:filters="filters"
        :search="search"
        :clear="clear"
        :initialForm="{
            reference: '',
            amount: '',
            payee_id: null,
            cost_center_id: null,
            expense_status_id: null,
            due_at: null,
            competence_date: null,
        }"
        :mapToForm="
            (item) => ({
                reference: item.reference,
                amount: item.amount,
                payee_id: item.payee?.id ?? null,
                cost_center_id: item.cost_center?.id ?? null,
                expense_status_id: item.status?.id ?? null,
                due_at: item.due_at,
                competence_date: item.competence_date,
            })
        "
    >
        <template #filters>
            <FilterText
                label="Buscar despesa"
                name="search_by"
                placeholder="CPF, CNPJ ou texto"
            />
            <FilterTabs label="Status" name="status" :tabs="statuses" />
        </template>

        <template #form="{ item, form }">
            <EditFields :expense="item" :form="form" />
        </template>

        <template #file="{ item, form }">
            <EditFile :expense="item" :form="form" />
        </template>
    </CrudIndexPage>
</template>
