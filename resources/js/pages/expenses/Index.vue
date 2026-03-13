<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';
import { type BreadcrumbItem } from '@/types';
import {CirclePlus, Eye, Pencil, Search, Trash} from 'lucide-vue-next';
import Pagination from '@/components/Pagination.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import DeleteButton from '@/components/DeleteButton.vue'
import AppButton from "@/components/AppButton.vue";
import AppInput from "@/components/AppInput.vue";
import debounce from 'lodash/debounce'

export interface Expense {
    id: number;
    notes: string;
}

/* Recebe os dados da controller via props usando Inertia */
const props = defineProps<{
    expenses: {
        data: Expense[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    name?: string;
    notes?: string;
}>();

/* Campos de pesquisa */
const filters = reactive({
    notes: props.notes || '',
});

const search = debounce(() => {
    router.get('/expenses', filters, {
        preserveState: true,
        preserveScroll: true,
    })
}, 400)

/* Define os breadcrumbs que serão exibidos no layout */
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Despesas', href: '' },
];

</script>

<template>
    <!-- Usa o layout padrão e passa os breadcrumbs para exibição -->
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

            <form
                @submit.prevent="search"
                class="grid grid-cols-4 gap-2"
            >
                <AppInput
                    v-model="filters.notes"
                    placeholder="Digite algo referente ao comprovante"
                    :icon="Search"
                    class="col-span-4 md:col-span-2"
                    @search="search"
                />

<!--                <div class="col-span-4 md:col-span-2 lg:col-span-1 flex items-center gap-2">-->
<!--                    <AppButton-->
<!--                        type="submit"-->
<!--                        label="Pesquisar"-->
<!--                        :icon="Search"-->
<!--                        class="flex-1"-->
<!--                    />-->

<!--                    <AppButton-->
<!--                        label="Limpar"-->
<!--                        variant="warning"-->
<!--                        :icon="Trash"-->
<!--                        @click="clearFilters"-->
<!--                        class="flex-1"-->
<!--                    />-->
<!--                </div>-->
             </form>

            <div class="m-4"></div>

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
                        <!-- Itera sobre os Despesas recebidos das props -->
                        <tr v-for="expense in props.expenses.data" :key="expense.id" class="table-body">
                            <!-- Colunas da tabela -->
                            <td class="table-row-body ">
                                {{ expense.id }}</td>
                            <td class="table-row-body ">
                                {{ expense.notes }}</td>
                            <td class="table-actions">
                                <div class="table-actions-align">

                                    <Link :href="`/expenses/${expense.id}`" class="btn-primary align-icon-btn">
                                    <Eye class="w-4 h-4" />
                                    <span>Visualizar</span>
                                    </Link>

                                    <Link :href="`/expenses/${expense.id}/edit`" class="btn-warning align-icon-btn">
                                    <Pencil class="w-4 h-4" />
                                    <span>Editar</span>
                                    </Link>

                                    <!-- Botão de apagar -->
                                    <!-- Usa o componente genérico -->
                                    <DeleteButton :url="`/expenses/${expense.id}`"
                                        title="Deseja realmente apagar esta tarefa?" />

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
