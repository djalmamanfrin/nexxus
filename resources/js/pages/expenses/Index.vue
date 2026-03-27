<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Search } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';
import FilterText from '@/components/filters/FilterText.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import AppTable from '@/components/base/AppTable.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';

export interface Expense {
    id: number;
    reference: string;
    amount: string;
    payee_id: string;
    cost_center_id: string;
    expense_category_id: string;
    due_at: string;
    competence_date: string;
}
const props = defineProps<{
    expenses: {
        data: Expense[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
    },
    '/expenses',
);

function getStatus(id: string | number) {
    const statusColorClasses: Record<string, string> = {
        green: 'bg-green-100 text-green-800',
        red: 'bg-red-100 text-red-800',
        yellow: 'bg-yellow-100 text-yellow-800',
        gray: 'bg-gray-100 text-gray-800',
    };
    const status = props.statuses.find((s) => s.value == id);
    const color = status?.color || 'gray';

    return {
        label: status?.label || 'Desconhecido',
        class: statusColorClasses[color] || statusColorClasses.gray,
    };
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Despesas',
        href: '',
        btn: { label: 'Novo Despesa', url: '/expenses' },
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="content-box">
            <FlashMessage />
            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filters"
                    @change="search"
                    @clear="clear"
                >
                    <FilterText
                        label="Digite algo referente a despesa"
                        name="search_by"
                        placeholder="Ex: cpf, cnpj ou qualquer texto no comprovante"
                        :icon="Search"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <AppTable
                :columns="[
                    { key: 'attachments', label: 'Imagem', align: 'left' },
                    { key: 'reference', label: 'Referência' },
                    { key: 'amount', label: 'Valor', type: 'money' },
                    {
                        key: 'payee_id',
                        label: 'Benifeciário',
                    },
                    {
                        key: 'cost_center_id',
                        label: 'C. de Custo',
                    },
                    { key: 'due_at', label: 'Vencimento', type: 'datetime' },
                    {
                        key: 'competence_date',
                        label: 'Competência',
                        type: 'datetime',
                    },
                    { key: 'created_at', label: 'Criado em', type: 'datetime' },
                ]"
                :items="props.expenses"
            >
                <template #cell-attachments="{ item }">
                    <span v-if="item.attachments?.length">
                        {{ item.attachments[0].original_name }}
                    </span>
                    <span v-else>-</span>
                </template>

                <template #cell-payment_status_id="{ item }">
                    <span
                        v-bind="
                            (() => {
                                const statusName = getStatus(
                                    item.payment_status_id,
                                );
                                return {
                                    class: [
                                        'rounded px-2 py-1 text-xs',
                                        statusName.class,
                                    ],
                                    innerText: statusName.label,
                                };
                            })()
                        "
                    />
                </template>
            </AppTable>
        </div>
    </AppLayout>
</template>
