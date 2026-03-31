<script setup lang="ts">
import { type BreadcrumbItem, CostCenter } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';
import CreateWork from '@/pages/works/CreateWork.vue';
import AppTextarea from '@/components/base/AppTextarea.vue';
import AppCreateModal from '@/components/AppCreateModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AppButtonWithModal from '@/components/base/AppButtonWithModal.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppInputMoney from '@/components/base/AppInputMoney.vue';
import Fields from '@/pages/cost_centers/Fields.vue';

const props = defineProps<{
    cost_centers: {
        data: CostCenter[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const url = '/cost-centers';

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
    },
    url,
);

const columns = [
    { key: 'work.name', label: 'Obra' },
    { key: 'code', label: 'Código' },
    { key: 'budget', label: 'Orçamento', type: 'money' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'C. de Custos',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <AppButtonWithModal
                label="Novo C. de Custo"
                title="Novo centro de custo"
                description="Cadastre centros de custo para organizar suas despesas
                por categoria e entender melhor para onde seu dinheiro está indo.
                Isso permite análises mais claras e decisões mais assertivas."
            >
                <template #default="{ close }">
                    <AppCreateModal
                        :url="url"
                        @success="close"
                        :initialData="{
                            code: null,
                            work_id: null,
                            budget: null,
                            cost_center_type_id: null,
                            description: null,
                        }"
                    >
                        <template #fields="{ form }">
                            <Fields :form="form" />
                        </template>
                    </AppCreateModal>
                </template>
            </AppButtonWithModal>
        </template>
        <CrudIndexPage
            :items="props.cost_centers"
            :columns="columns"
            :base-url="url"
            v-model:filters="filters"
            :search="search"
            :clear="clear"
            :initialForm="{
                work_id: null,
                code: null,
                budget: null,
                start_date: null,
                expected_end_date: null,
                description: null,
            }"
            :mapToForm="
                (item) => ({
                    work_id: item.work?.id ?? null,
                    code: item.code,
                    budget: item.budget,
                    start_date: item.start_date,
                    expected_end_date: item.expected_end_date,
                    description: item.description,
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

            <template #title> Centro de Custo </template>

            <template #form="{ item, form }">
                <AppFormLayout :item="item">
                    <Fields :form="form" />
                </AppFormLayout>
            </template>
        </CrudIndexPage>
    </AppLayout>
</template>
