<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Link } from '@inertiajs/vue3'
import { List, Pencil } from 'lucide-vue-next'
import FlashMessage from '@/components/FlashMessage.vue'
import DeleteButton from '@/components/DeleteButton.vue'

export interface Expense {
    id: number;
    notes: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    expense: Expense;
}>();

/* Define os breadcrumbs exibidos no topo da página */
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Despesas', href: '/expenses' },
    { title: 'Visualizar Despesas', href: '' },
];

</script>

<template>
    <!-- Usa o layout principal do sistema -->
    <AppLayout :breadcrumbs="breadcrumbItems">
        <!-- Define o título da aba -->

        <Head title="Despesas" />

        <!-- Container principal -->
        <div class="content-box">

            <!-- Título -->
            <div class="content-box-header">
                <h3 class="content-box-title">Detalhes do Veículo</h3>
                <div class="content-box-btn">

                    <Link href="/expenses" class="btn-info align-icon-btn">
                    <List class="w-4 h-4" />
                    <span>Listar</span>
                    </Link>

                    <Link :href="`/expenses/${props.expense.id}/edit`" class="btn-warning align-icon-btn">
                    <Pencil class="w-4 h-4" />
                    <span>Editar</span>
                    </Link>

                    <DeleteButton :url="`/expenses/${props.expense.id}`" title="Deseja realmente apagar esta tarefa?" />

                </div>
            </div>

            <!-- Apresentar a mensagem de sucesso ou erro -->
            <FlashMessage />

            <!-- Card com informações -->
            <div class="detail-box">

                <p><strong class="title-detail-content">ID:</strong> <span class="detail-content">{{ props.expense.id
                }}</span></p>
                <p><strong class="title-detail-content">Anotações:</strong> <span class="detail-content">{{
                        props.expense.notes
                    }}</span></p>
                <p><strong class="title-detail-content">Criado em:</strong> <span class="detail-content">{{ new
                    Date(props.expense.created_at).toLocaleString() }}</span></p>
                <p><strong class="title-detail-content">Atualizado em:</strong> <span class="detail-content">{{ new
                    Date(props.expense.updated_at).toLocaleString() }}</span></p>
            </div>

        </div>
    </AppLayout>
</template>
