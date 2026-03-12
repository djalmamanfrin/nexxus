<script setup lang="ts">

import AppLayout from '@/layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { CirclePlus, List } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';

/* Define os breadcrumbs da página */
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Despesas', href: '/expenses' },
    { title: 'Nova Despesa', href: '' }
];

/* Cria o formulário com os campos e valores iniciais */
const form = useForm({
    notes: '',
});

/* Envia o formulário para o backend */
const submit = () => {
    form.post('/expenses');
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Nova Despesa" />

        <div class="content-box">
            <div class="content-box-header">
                <h3 class="content-box-title">Nova Despesa</h3>
                <div class="content-box-btn">
                    <Link href="/expenses" class="btn-info align-icon-btn">
                    <List class="w-4 h-4" />
                    <span>Listar</span>
                    </Link>
                </div>
            </div>

            <FlashMessage />

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="notes" class="form-label">Anotações</label>
                    <textarea id="started_at" v-model="form.notes" type="datetime-local" class="form-input" required />
                    <div v-if="form.errors.notes" class="form-error">{{ form.errors.notes }}</div>
                </div>

                <div class="mb-4">
                    <span class="required-field">* Campo obrigatório</span>
                </div>

                <button type="submit" class="btn-success align-icon-btn" :disabled="form.processing">
                    <CirclePlus class="w-4 h-4" />
                    <span>{{ form.processing ? 'Salvando...' : 'Nova' }}</span>
                </button>
            </form>
        </div>
    </AppLayout>
</template>
