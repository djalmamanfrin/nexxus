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

export interface Payment {
    id: number;
    expense_id: string;
    bank_account_id: string;
    payment_status_id: string;
    payment_type_id: string;
    amount: string;
    paid_at: string;
    created_at: string;
}
const props = defineProps<{
    payments: {
        data: Payment[];
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
    '/payments',
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
        title: 'Pagamentos',
        href: '',
        btn: { label: 'Novo Pagamento', url: '/payments' },
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
                        label="Digite algo referente ao pagamento"
                        name="search_by"
                        placeholder="Ex: cpf, cnpj ou qualquer texto no comprovante"
                        :icon="Search"
                    />
                    <FilterTabs label="Status" name="status" :tabs="statuses" />
                    <!--                    <FilterSelect-->
                    <!--                        :options="statuses"-->
                    <!--                        :selectedValue="status"-->
                    <!--                        label="Status"-->
                    <!--                        name="status"-->
                    <!--                        width="w-56"-->
                    <!--                    />-->
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <AppTable
                :columns="[
                    { key: 'attachments', label: 'Imagem', align: 'left' },
                    { key: 'amount', label: 'Valor', type: 'money' },
                    {
                        key: 'payment_status_id',
                        label: 'Status',
                        type: 'payment_status',
                    },
                    { key: 'paid_at', label: 'Pago em', type: 'datetime' },
                    { key: 'created_at', label: 'Criado em', type: 'datetime' },
                ]"
                :items="props.payments"
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
