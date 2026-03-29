<script setup lang="ts">
import { type BreadcrumbItem, Expense } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import EditFields from '@/pages/expenses/EditFields.vue';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFileInput from '@/components/base/AppFileInput.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import CreateCostCenter from '@/pages/cost_centers/CreateCostCenter.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';
import AppLabel from '@/components/base/AppLabel.vue';
import { formatDateTime } from '@/lib/date';

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
            <FilterTabs
                v-if="statuses?.length"
                label="Status"
                name="status"
                :tabs="statuses"
            />
        </template>

        <template #form="{ item, form }">
            <AppFormLayout :item="item">
                <AppInput
                    v-model="form.reference"
                    :error="form.errors.reference"
                    label="Referencia"
                />
                <AppInput
                    v-model="form.amount"
                    :error="form.errors.amount"
                    label="Valor"
                    mask="currency"
                />

                <AppSelect
                    v-model="form.payee_id"
                    url="payees/options"
                    label="Beneficiário"
                    name="payee_id"
                />

                <AppSelectWithModal
                    v-model="form.cost_center_id"
                    showCreate
                    @created="({ field, value }) => (form[field] = value)"
                    :createComponent="CreateCostCenter"
                    url="cost-centers/options"
                    label="C. de Custo"
                    name="cost_center_id"
                    width="w-56"
                    title="Novo C. de Custo"
                    description="Como deseja nomear?"
                />

                <AppInput
                    v-model="form.due_at"
                    :error="form.errors.due_at"
                    label="Vencimento em"
                    type="datetime-local"
                />
                <AppInput
                    v-model="form.competence_date"
                    :error="form.errors.competence_date"
                    label="Competencia"
                    type="datetime-local"
                />
            </AppFormLayout>
        </template>

        <template #file="{ item, form }">
            <AppFileInput :attachments="item?.attachments" :form="form" />
        </template>
    </CrudIndexPage>
</template>
