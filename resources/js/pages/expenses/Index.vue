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

export interface Expense {
    id: number;
    notes: string;
}
const props = defineProps<{
    expenses: {
        data: Expense[];
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
        created_to: props.created_to || '',
    },
    '/expenses',
);

/* Define os breadcrumbs que serão exibidos no layout */
const breadcrumbItems: BreadcrumbItem[] = [{ title: 'Despesas', href: '' }];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Despesas" />
        <div class="content-box">
            <div class="content-box-header">
                <h3 class="content-box-title">Despesas</h3>
                <div class="content-box-btn">
                    <AppButton
                        href="/expenses/create"
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
                        label="Digite algo referente ao comprovante"
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
                            <th class="table-row-header">Anotações</th>
                            <th class="table-row-header">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="expense in props.expenses.data"
                            :key="expense.id"
                            class="table-body"
                        >
                            <td class="table-row-body">
                                {{ expense.id }}
                            </td>
                            <td class="table-row-body">
                                {{ expense.notes }}
                            </td>
                            <td class="table-actions">
                                <div class="table-actions-align">
                                    <Link
                                        :href="`/expenses/${expense.id}`"
                                        class="btn-primary align-icon-btn"
                                    >
                                        <Eye class="h-4 w-4" />
                                        <span>Visualizar</span>
                                    </Link>

                                    <Link
                                        :href="`/expenses/${expense.id}/edit`"
                                        class="btn-warning align-icon-btn"
                                    >
                                        <Pencil class="h-4 w-4" />
                                        <span>Editar</span>
                                    </Link>

                                    <DeleteButton
                                        :url="`/expenses/${expense.id}`"
                                        title="Deseja realmente apagar esta tarefa?"
                                    />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :links="props.expenses.links" />
            </div>
        </div>
    </AppLayout>
</template>
