<script setup lang="ts">
import { computed } from 'vue';
import { useCrud } from '@/composables/useCrud';
import { type BreadcrumbItem, Expense } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { SelectOption } from '@/types/select';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import AppFileInput from '@/components/base/AppFileInput.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import CreateCostCenter from '@/pages/cost_centers/CreateCostCenter.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';
import AppUploadModal from '@/components/base/AppUploadModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppInputMoney from '@/components/base/AppInputMoney.vue';
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import CrudTable from '@/components/crud/CrudTable.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import CrudDrawer from '@/components/crud/CrudDrawer.vue';
import Fields from '@/pages/cost_centers/Fields.vue';
import AppCreateModal from '@/components/AppCreateModal.vue';

const props = defineProps<{
    expenses: {
        data: Expense[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    statuses: SelectOption[];
    search_by?: string;
    status?: string | number | null;
}>();

const columns = [
    { key: 'attachments', label: 'Imagem', align: 'left', type: 'attachment' },
    { key: 'amount.formatted', label: 'Valor' },
    {
        key: 'status.name',
        label: 'Status',
        type: 'badge',
        color: 'status.color',
    },
    { key: 'cost_center.label', label: 'C. de Custo' },
    { key: 'due_at.formatted', label: 'Vencimento' },
    { key: 'competence_date.formatted', label: 'Competência' },
    { key: 'created_at.formatted', label: 'Criado em' },
];

const workSchema = {
    entity: 'expenses',
    filters: {
        search_by: props.search_by || '',
        status: props.status || null,
    },
    actions: [
        { name: 'edit', title: 'Editar', icon: PencilIcon },
        { name: 'delete', title: 'Excluir', icon: Trash2Icon },
    ],
    form: {
        initial: {
            reference: '',
            amount: '',
            payee_id: null,
            cost_center_id: null,
            due_at: null,
            competence_date: null,
        },
        map: (item: any) => ({
            reference: item.reference,
            amount: Number(item.amount.value),
            payee_id: item.payee?.id ?? null,
            cost_center_id: item.cost_center?.id ?? null,
            due_at: item.due_at.value,
            competence_date: item.competence_date.value,
        }),
    },
    tabs: [
        { name: 'form', label: 'Informações' },
        { name: 'file', label: 'Anexos' },
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
} = useCrud(workSchema);

const emit = defineEmits(['update:filters']);
const filtersProxy = computed({
    get: () => filters,
    set: (value) => emit('update:filters', value),
});

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
            <AppUploadModal label="Nova Despesa" :url="baseUrl" />
        </template>
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
                :items="props.expenses"
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

                        <AppSelect
                            v-model="form.cost_center_id"
                            url="cost-centers/options"
                            label="Centro de Custo"
                            name="cost_center_id"
                        />

                        <!--                        <AppSelectWithModal-->
                        <!--                            v-model="form.cost_center_id"-->
                        <!--                            showCreate-->
                        <!--                            @created="-->
                        <!--                                ({ field, value }) => (form[field] = value)-->
                        <!--                            "-->
                        <!--                            url="cost-centers/options"-->
                        <!--                            label="C. de Custo"-->
                        <!--                            name="cost_center_id"-->
                        <!--                            width="w-56"-->
                        <!--                            title="Novo C. de Custo"-->
                        <!--                            description="Como deseja nomear?"-->
                        <!--                        >-->
                        <!--                            <template #create>-->
                        <!--                                <AppCreateModal-->
                        <!--                                    url="cost-centers"-->
                        <!--                                    @success="close"-->
                        <!--                                    :initialData="{-->
                        <!--                                        expense_id: item.id,-->
                        <!--                                        work_id: null,-->
                        <!--                                        cost_center_type_id: null,-->
                        <!--                                        code: '',-->
                        <!--                                        budget: 0,-->
                        <!--                                        description: '',-->
                        <!--                                    }"-->
                        <!--                                >-->
                        <!--                                    <template #fields="{ form }">-->
                        <!--                                        <Fields :form="form" />-->
                        <!--                                    </template>-->
                        <!--                                </AppCreateModal>-->
                        <!--                            </template>-->
                        <!--                        </AppSelectWithModal>-->

                        <AppInputDate
                            v-model="form.due_at"
                            :error="form.errors.due_at"
                            label="Vencimento em"
                        />
                        <AppInputDate
                            v-model="form.competence_date"
                            :error="form.errors.competence_date"
                            label="Competencia"
                            type="month"
                        />
                    </AppFormLayout>
                </template>

                <template #file="{ item, form }">
                    <AppFileInput
                        :attachments="item?.attachments"
                        :form="form"
                    />
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
