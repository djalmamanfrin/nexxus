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
import AppUploadModal from '@/components/base/AppUploadModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppInputMoney from '@/components/base/AppInputMoney.vue';

const props = defineProps<{
    expenses: {
        data: Expense[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    statuses: SelectOption[];
    search_by?: string;
    status?: string | number | null;
}>();

const url = '/expenses';

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
        status: props.status || null,
    },
    url,
);

const columns = [
    { key: 'attachments', label: 'Imagem', align: 'left', type: 'attachment' },
    { key: 'amount.formatted', label: 'Valor' },
    { key: 'status.name', label: 'Status' },
    { key: 'cost_center.code', label: 'C. de Custo' },
    { key: 'due_at', label: 'Vencimento'},
    { key: 'created_at', label: 'Criado em' },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Despesas',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <AppUploadModal label="Nova Despesa" :url="url" />
        </template>
        <CrudIndexPage
            :items="props.expenses"
            :columns="columns"
            :base-url="url"
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

            <template #title> Despesas </template>

            <template #form="{ item, form }">
                <AppFormLayout :item="item">
                    <AppInput
                        v-model="form.reference"
                        :error="form.errors.reference"
                        label="Referencia"
                    />
                    <AppInputMoney
                        v-model="form.amount"
                        :error="form.errors.amount"
                        label="Valor"
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

                    <AppInputDate
                        v-model="form.due_at"
                        :error="form.errors.due_at"
                        label="Vencimento em"
                    />
                    <AppInputDate
                        v-model="form.competence_date"
                        :error="form.errors.competence_date"
                        label="Competencia"
                    />
                </AppFormLayout>
            </template>

            <template #file="{ item, form }">
                <AppFileInput :attachments="item?.attachments" :form="form" />
            </template>
        </CrudIndexPage>
    </AppLayout>
</template>
