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
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { useCrud } from '@/composables/useCrud';
import { computed } from 'vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import CrudTable from '@/components/crud/CrudTable.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import CrudDrawer from '@/components/crud/CrudDrawer.vue';
import { SelectOption } from '@/types/select';

const props = defineProps<{
    cost_centers: {
        data: CostCenter[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
    statuses?: SelectOption[];
    status?: string;
}>();

const columns = [
    { key: 'work.name', label: 'Obra', align: 'left' },
    { key: 'code', label: 'Código' },
    { key: 'budget.value', label: 'Orçamento' },
    {
        key: 'status.name',
        label: 'Status',
        type: 'badge',
        color: 'status.color',
    },
    { key: 'start_date.formatted', label: 'Início' },
    { key: 'expected_end_date.formatted', label: 'Término' },
    { key: 'created_at.formatted', label: 'Criado em' },
];

const costCenterSchema = {
    entity: 'cost-centers',
    filters: {
        search_by: props.search_by || '',
    },
    actions: [
        { name: 'edit', title: 'Editar', icon: PencilIcon },
        { name: 'delete', title: 'Excluir', icon: Trash2Icon },
    ],
    form: {
        initial: {
            work_id: null,
            cost_center_type_id: null,
            code: null,
            budget: null,
            start_date: null,
            expected_end_date: null,
            description: null,
        },

        map: (item: any) => ({
            work_id: item.work?.id ?? null,
            cost_center_type_id: item.type?.id ?? null,
            code: item.code,
            budget: Number(item.budget.value),
            start_date: item.start_date.value,
            expected_end_date: item.expected_end_date.value,
            description: item.description,
        }),
    },
    tabs: [{ name: 'form', label: 'Informações' }],
};

const {
    open,
    baseUrl,
    filters,
    search,
    clear,
    selectedItem,
    tabs,
    actions,
    handleAction,
    handleSave,
} = useCrud(costCenterSchema);

const emit = defineEmits(['update:filters']);
const filtersProxy = computed({
    get: () => filters,
    set: (value) => emit('update:filters', value),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'C. de Custos',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="content-box">
            <FlashMessage />

            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filtersProxy"
                    @change="search"
                    @clear="clear"
                >
                    <FilterText
                        label="Buscar despesa"
                        name="search_by"
                        placeholder="CPF, CNPJ ou texto"
                    />

                    <FilterTabs
                        v-if="statuses?.length"
                        label="Status"
                        name="is_active"
                        :tabs="statuses"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <CrudTable
                :items="props.cost_centers"
                :columns="columns"
                :actions="actions"
                @action="handleAction"
            />
            <CrudDrawer
                v-model:open="open"
                :tabs="tabs"
                :item="selectedItem"
                @save="handleSave"
            >
                <template #title> Centro de Custo </template>

                <template #form="{ item, form }">
                    <AppFormLayout :item="item">
                        <Fields :form="form" />
                    </AppFormLayout>
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
