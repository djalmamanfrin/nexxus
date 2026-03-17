<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { CirclePlus, Eye, Pencil, Search } from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import DeleteButton from '@/components/DeleteButton.vue';
import AppButton from '@/components/AppButton.vue';
import FilterDate from '@/components/filters/FilterDate.vue';
import FilterSelect from '@/components/filters/FilterSelect.vue';
import FilterText from '@/components/filters/FilterText.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';

export interface Payment {
    id: number;
    bank_account_id: string;
    expense_id: string;
    status: string;
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
    status?: string;
    search_by?: string;
    paid_at?: string;
    created_to?: string;
}>();

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
        status: props.status || '',
        paid_at: props.paid_at || '',
        created_at: props.created_at || '',
    },
    '/payments',
);

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
                    <AppButton
                        href="/payments/create"
                        label="Nova Despesa"
                        :icon="CirclePlus"
                        variant="success"
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
                        label="Status"
                        name="status"
                        width="w-56"
                        :options="statuses"
                    />
                    <FilterDate label="Criado em" name="created_to" />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="table-header">
                            <th class="table-row-header">ID</th>
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
                                {{ payment.id }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.bank_account_id }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.expense_id }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.status }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.amount }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.paid_at }}
                            </td>
                            <td class="table-row-body">
                                {{ payment.created_at }}
                            </td>
                            <td class="table-actions">
                                <div class="table-actions-align">
                                    <Link
                                        :href="`/payments/${payment.id}`"
                                        class="btn-primary align-icon-btn"
                                    >
                                        <Eye class="h-4 w-4" />
                                        <span>Visualizar</span>
                                    </Link>

                                    <Link
                                        :href="`/payments/${payment.id}/edit`"
                                        class="btn-warning align-icon-btn"
                                    >
                                        <Pencil class="h-4 w-4" />
                                        <span>Editar</span>
                                    </Link>

                                    <DeleteButton
                                        :url="`/payments/${payment.id}`"
                                        title="Deseja realmente apagar este pagamento?"
                                    />
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
