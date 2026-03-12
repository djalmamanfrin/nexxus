<script setup lang="ts">
/* Importa o layout padrão do sistema */
import AppLayout from '@/layouts/AppLayout.vue'

/* Importa os componentes do Inertia */
import { Link, Head, useForm } from '@inertiajs/vue3'

/* Importa tipos e ícones */
import { type BreadcrumbItem } from '@/types'
import { Save, List, Eye } from 'lucide-vue-next'

/* Importar o componente para apresentar a mensagem de sucesso e erro */
import FlashMessage from '@/components/FlashMessage.vue';

/* Recebe o Tarefa da Controller */
const props = defineProps<{
    expense: {
        id: number
        notes: string
    }
}>()

/* Define os breadcrumbs */
const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Despesas', href: '/expenses' },
    { title: 'Editar Despesa', href: '' }
]

// Formatar a data
function formatDateTime(datetime?: string | null): string {
    if (!datetime) return '';
    const date = new Date(datetime);
    const tzOffset = date.getTimezoneOffset() * 60000;
    return new Date(date.getTime() - tzOffset).toISOString().slice(0, 16);
}

/* Cria o formulário com os valores atuais */
const form = useForm({
    notes: props.expense.notes,
})

/* Envia os dados para o backend */
const submit = () => {
    form.put(`/expenses/${props.expense.id}`)
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Editar Despesa" />

        <div class="content-box">
            <div class="content-box-header">
                <h3 class="content-box-title">Editar Despesa</h3>
                <div class="content-box-btn">
                    <Link href="/expenses" class="btn-info align-icon-btn">
                    <List class="w-4 h-4" />
                    <span>Listar</span>
                    </Link>

                    <Link :href="`/expenses/${props.expense.id}`" class="btn-primary align-icon-btn">
                    <Eye class="w-4 h-4" />
                    <span>Visualizar</span>
                    </Link>
                </div>
            </div>

            <!-- Apresentar a mensagem de sucesso ou erro -->
            <FlashMessage />

            <!-- Formulário -->
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="notes" class="form-label">Anotações</label>
                    <textarea id="notes" v-model="form.notes" type="text" class="form-input"
                           placeholder="Anotações" />
                    <div v-if="form.errors.notes" class="form-error">{{ form.errors.notes }}</div>
                </div>

                <div class="mb-4">
                    <span class="required-field">* Campo obrigatório</span>
                </div>

                <button type="submit" class="btn-success align-icon-btn" :disabled="form.processing">
                    <Save class="w-4 h-4" />
                    <span>{{ form.processing ? 'Salvando...' : 'Salvar' }}</span>
                </button>
            </form>
        </div>
    </AppLayout>
</template>
