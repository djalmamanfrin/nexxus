<script setup lang="ts">
import { type BreadcrumbItem, CostCenter } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';
import CreateWork from '@/pages/payees/CreateWork.vue';

const props = defineProps<{
    cost_centers: {
        data: CostCenter[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
    },
    '/cost-centers',
);

const columns = [
    { key: 'work.name', label: 'Obra' },
    { key: 'code', label: 'Código' },
    { key: 'budget', label: 'Orçamento', type: 'money' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'C. de Custos',
        href: '',
        btn: { label: 'Nova C. de Custo', url: '/cost-centers' },
    },
];
</script>

<template>
    <CrudIndexPage
        :items="props.cost_centers"
        :columns="columns"
        base-url="/cost-centers"
        :breadcrumbs="breadcrumbItems"
        v-model:filters="filters"
        :search="search"
        :clear="clear"
        :initialForm="{
            code: '',
            budget: '',
            work_id: null,
        }"
        :mapToForm="
            (item) => ({
                code: item.code,
                budget: item.budget,
                work_id: item.work?.id ?? null,
            })
        "
    >
        <template #filters>
            <FilterText
                label="Buscar despesa"
                name="search_by"
                placeholder="CPF, CNPJ ou texto"
            />
        </template>

        <template #form="{ item, form }">
            <AppFormLayout :item="item">
                <AppInput v-model="form.code" label="Referencia" />
                <AppInput v-model="form.budget" label="Valor" mask="currency" />

                <AppSelectWithModal
                    v-model="form.work_id"
                    showCreate
                    @created="({ field, value }) => (form[field] = value)"
                    :createComponent="CreateWork"
                    url="cost-centers/options"
                    label="Obra"
                    name="work_id"
                    title="Nova Obra"
                    description="Como deseja nomear?"
                />
            </AppFormLayout>
        </template>
    </CrudIndexPage>
</template>
