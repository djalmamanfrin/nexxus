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
import AppImagePreview from '@/components/base/AppImagePreview.vue';
import AppTable from '@/components/base/AppTable.vue';
import Upload from '@/pages/attachments/Upload.vue';

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
                    <Upload
                        url="/payments"
                        label="Novo Pagamento"
                    />
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

                <template #cell-payment_status_id="{ item }">
                    <span
                        v-bind="
                            (() => {
                                const status = getStatus(item.payment_status_id);
                                return {
                                    class: [
                                        'rounded px-2 py-1 text-xs',
                                        status.class,
                                    ],
                                    innerText: status.label,
                                };
                            })()
                        "
                    />
                </template>
            </AppTable>
        </div>
    </AppLayout>
</template>
