<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { PencilIcon, Search, Trash2Icon } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';
import FilterText from '@/components/filters/FilterText.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import AppTable from '@/components/base/AppTable.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import SidebarDrawer from '@/components/ui/sidebar/SidebarDrawer.vue';
import EditFields from '@/pages/expenses/EditFields.vue';
import EditFile from '@/pages/expenses/EditFile.vue';
import AppButton from '@/components/AppButton.vue';
import SidebarDrawerTabs from '@/components/ui/sidebar/SidebarDrawerTabs.vue';
import SidebarDrawerTab from '@/components/ui/sidebar/SidebarDrawerTab.vue';
import SidebarDrawerPanel from '@/components/ui/sidebar/SidebarDrawerPanel.vue';

export interface Expense {
    id: number;
    reference: string;
    amount: string;
    payee_id: string;
    cost_center_id: string;
    expense_status_id: string;
    expense_category_id: string;
    due_at: string;
    competence_date: string;
}

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

const open = ref(false);
const selectedItem = ref<Expense | null>(null);

const dataForm = useForm({
    reference: '',
    amount: '',
    payee_id: '',
    cost_center_id: '',
    expense_status_id: '',
    due_at: '',
    competence_date: '',
});

const fileForm = useForm({
    attachment: null,
});

const handleEdit = (item: Expense) => {
    selectedItem.value = item;

    dataForm.defaults({
        reference: item.reference,
        amount: item.amount,
        payee_id: item.payee_id,
        cost_center_id: item.cost_center_id,
        expense_status_id: item.expense_status_id,
        due_at: item.due_at,
        competence_date: item.competence_date,
    });
    dataForm.reset();
    open.value = true;
};

const handleDelete = (item: Expense) => {
    if (!confirm('Tem certeza que deseja excluir esta despesa?')) return;

    router.delete(`/expenses/${item.id}`, {
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
        dataForm.patch(`/expenses/${id}`, {
            preserveState: true,
            onSuccess: () => {
                open.value = false;
                dataForm.reset();
            },
        });
    }

    if (fileForm.isDirty) {
        fileForm.post(`/expenses/${id}/attachments`, {
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
        title: 'Despesas',
        href: '',
        btn: { label: 'Nova Despesa', url: '/expenses' },
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="content-box">
            <FlashMessage />

            <!-- FILTROS -->
            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filters"
                    @change="search"
                    @clear="clear"
                >
                    <FilterText
                        label="Buscar despesa"
                        name="search_by"
                        placeholder="CPF, CNPJ ou texto"
                        :icon="Search"
                    />
                    <FilterTabs label="Status" name="status" :tabs="statuses" />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <AppTable
                :columns="[
                    { key: 'attachments', label: 'Imagem', align: 'left' },
                    { key: 'amount', label: 'Valor', type: 'money' },
                    { key: 'status.name', label: 'Status' },
                    { key: 'cost_center.code', label: 'C. de Custo' },
                    { key: 'due_at', label: 'Vencimento', type: 'date' },
                    { key: 'created_at', label: 'Criado em', type: 'datetime' },
                ]"
                :items="props.expenses"
                @view="handleEdit"
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
                        title="Editar despesa"
                        variant="link"
                        :icon="PencilIcon"
                    />
                    <AppButton
                        @click="handleDelete(item)"
                        title="Excluir despesa"
                        variant="link"
                        :icon="Trash2Icon"
                    />
                </template>
            </AppTable>
        </div>

        <SidebarDrawer :open="open" @close="open = false" @save="handleSave">
            <template #title>Comprovante de Despesa</template>

            <SidebarDrawerTabs>
                <SidebarDrawerTab name="info" label="Informações" />
                <SidebarDrawerTab name="arquivo" label="Arquivo" />
            </SidebarDrawerTabs>

            <SidebarDrawerPanel name="info">
                <EditFields :expense="selectedItem" :form="dataForm" />
            </SidebarDrawerPanel>
            <SidebarDrawerPanel name="arquivo">
                <EditFile :expense="selectedItem" :form="fileForm" />
            </SidebarDrawerPanel>
        </SidebarDrawer>
    </AppLayout>
</template>
