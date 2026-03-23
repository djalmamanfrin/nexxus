<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Search } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';
import FilterSelect from '@/components/filters/FilterSelect.vue';
import FilterText from '@/components/filters/FilterText.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import Create from '@/pages/payments/Create.vue';
import AppImagePreview from '@/components/base/AppImagePreview.vue';
import AppTable from '@/components/base/AppTable.vue';

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

function getStatusLabel(id: string | number) {
    return props.statuses.find((s) => s.value == id)?.label ?? '-';
}

/* Define os breadcrumbs que serão exibidos no layout */
const breadcrumbItems: BreadcrumbItem[] = [{ title: 'Pagamentos', href: '' }];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Pagamentos" />
        <div class="content-box">
            <div class="content-box-header">
                <h3 class="content-box-title">Pagamentos</h3>
                <div class="content-box-btn">
                    <Create />
                </div>
            </div>
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
                    <FilterSelect
                        :options="statuses"
                        :selectedValue="status"
                        label="Status"
                        name="status"
                        width="w-56"
                    />
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
                    { key: 'created_at', label: 'Criado em', type: 'datetime' },
                ]"
                :items="props.payments"
            >
                <!-- UI COMPLEXA → SLOT -->
                <template #cell-attachments="{ item }">
                    <AppImagePreview
                        v-if="item.attachments?.length"
                        :file="item.attachments[0]"
                    />
                    <span v-else>-</span>
                </template>

                <!-- STATUS COM BADGE -->
                <template #cell-payment_status_id="{ item }">
                    <span
                        class="rounded bg-green-100 px-2 py-1 text-xs text-green-800"
                    >
                        {{ getStatusLabel(item.payment_status_id) }}
                    </span>
                </template>
            </AppTable>
        </div>
    </AppLayout>
</template>
