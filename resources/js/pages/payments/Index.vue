<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Eye, Pencil, Search, Trash } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import FilterSelect from '@/components/filters/FilterSelect.vue';
import FilterText from '@/components/filters/FilterText.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import Create from '@/pages/payments/Create.vue';
import { formatDate, formatDateTime, formatRelative } from '@/lib/date';
import AppImagePreview from '@/components/base/AppImagePreview.vue';

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
    paymentTypes: SelectOption[];
}>();

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
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
                        label="Status"
                        name="status"
                        width="w-56"
                        :options="statuses"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="table-header">
                            <th class="table-row-header">Imagem</th>
                            <th class="table-row-header">Conta</th>
                            <th class="table-row-header">Despesa</th>
                            <th class="table-row-header">Status</th>
                            <th class="table-row-header">Valor</th>
                            <th class="table-row-header">Pago em</th>
                            <th class="table-row-header">Criado em</th>
                            <th class="table-row-header">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="payment in props.payments.data"
                            :key="payment.id"
                            class="table-body"
                        >
                            <td class="table-row-body">
                                <AppImagePreview
                                    v-if="payment.attachments.length > 0"
                                    :file="payment.attachments[0]"
                                />
                                <span v-else>-</span>
                            </td>
                            <td class="table-row-body">
                                {{ payment.bank_account_id ?? 'N/A' }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.expense_id ?? 'N/A' }}
                            </td>
                            <td class="table-row-body">
                                {{ getStatusLabel(payment.payment_status_id) }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.amount }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.paid_at ?? 'N/A' }}
                            </td>
                            <td class="table-row-body">
                                {{ formatDateTime(payment.created_at) }}
                            </td>
                            <td class="table-actions">
                                <div class="table-actions-align gap-2">
                                    <Link
                                        :href="`/payments/${payment.id}`"
                                        class="cursor-pointer"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </Link>

                                    <Link
                                        :href="`/payments/${payment.id}/edit`"
                                        class="cursor-pointer"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Link>

                                    <Link
                                        :href="`/payments/${payment.id}`"
                                        class="cursor-pointer"
                                    >
                                        <Trash class="h-4 w-4" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="props.payments.links" />
            </div>
        </div>
    </AppLayout>
</template>
