<script setup lang="ts">
import { type BreadcrumbItem, CostCenter, CostCenterType } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import Fields from '@/pages/cost_centers/Fields.vue';
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { useCrud } from '@/composables/useCrud';
import { computed } from 'vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import CrudTable from '@/components/crud/CrudTable.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import CrudDrawer from '@/components/crud/CrudDrawer.vue';
import { SelectOption } from '@/types/select';
import AppLayout from '@/layouts/AppLayout.vue';
import AppButtonWithModal from '@/components/base/AppButtonWithModal.vue';
import AppCreateModal from '@/components/AppCreateModal.vue';
import TypeFields from '@/pages/cost_centers/TypeFields.vue';

const props = defineProps<{
    cost_centers: {
        data: CostCenter[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    types: {
        data: CostCenterType[];
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
        status: props.status || '',
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
    tabs: [
        { name: 'form', label: 'Informações' },
        { name: 'cost_center_types', label: 'Tipos' },
    ],
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
                        name="status"
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
                <template #cost_center_types="{ form, item }">
                    <AppButtonWithModal
                        label="Novo tipo de centro de custo"
                        title="Novo tipo de centro de custo"
                        description=""
                    >
                        <template #default="{ close }">
                            <AppCreateModal
                                url="cost-center-types"
                                @success="close"
                                :initialData="{
                                    cost_center_type_id: item.id,
                                    name: null,
                                    code: null,
                                }"
                            >
                                <template #fields="{ form }">
                                    <TypeFields :form="form" />
                                </template>
                            </AppCreateModal>
                        </template>
                    </AppButtonWithModal>
                    <div class="my-4"></div>
                    <CrudTable
                        :items="props.types"
                        :columns="[
                            { key: 'name', label: 'Nome' },
                            { key: 'code', label: 'Código' },
                        ]"
                    />
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
