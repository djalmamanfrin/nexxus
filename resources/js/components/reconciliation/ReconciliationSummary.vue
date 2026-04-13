<script setup lang="ts">
import { computed } from 'vue';
import type { Expense, Payment } from '@/types';
import AppButton from '@/components/AppButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    expense: Expense | null;
    payments: Payment[];
}>();

const expenseAmount = computed(() => {
    return props.expense?.amount?.value ?? 0;
});

const totalPayments = computed(() => {
    return props.payments.reduce((total, payment) => {
        return total + (payment.amount?.value ?? 0);
    }, 0);
});

const difference = computed(() => {
    return totalPayments.value - expenseAmount.value;
});

const status = computed(() => {
    if (!props.expense) return 'Nenhuma despesa selecionada';
    if (totalPayments.value === 0) return 'Pendente';
    if (totalPayments.value < expenseAmount.value) return 'Parcial';
    if (totalPayments.value === expenseAmount.value) return 'Conciliado';
    return 'Excedente';
});

const form = useForm({
    expense_id: null as number | null,
    payments: [] as { id: number; amount: number }[],
});

function submit() {
    if (!props.expense || !props.payments.length) return;

    form.expense_id = props.expense.id;
    form.payments = props.payments.map((payment) => ({
        id: payment.id,
        amount: payment.amount?.value ?? 0,
    }));

    form.post('/reconciliations', {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
    >
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold">Conciliação</h3>

            <form @submit.prevent="submit">
                <AppButton
                    type="submit"
                    label="Salvar conciliação"
                    variant="success"
                    :disabled="!expense || !payments.length"
                />
            </form>
        </div>

        <div v-if="expense" class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Despesa</span>
                <span class="font-medium">{{ expense.reference }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Valor da despesa</span>
                <span class="font-medium">{{
                    expense.amount?.formatted ?? '---'
                }}</span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Total dos pagamentos</span>
                <span class="font-medium">
                    {{
                        totalPayments.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                        })
                    }}
                </span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Diferença</span>
                <span class="font-medium">
                    {{
                        difference.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                        })
                    }}
                </span>
            </div>

            <div
                class="flex items-center justify-between border-t pt-3 dark:border-neutral-800"
            >
                <span class="text-sm text-gray-500">Status</span>
                <span
                    class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700 dark:bg-blue-900/40 dark:text-blue-300"
                >
                    {{ status }}
                </span>
            </div>

            <div v-if="payments.length" class="mt-4">
                <h4 class="mb-2 text-sm font-semibold text-gray-600">
                    Pagamentos selecionados
                </h4>
                <ul class="space-y-2">
                    <li
                        v-for="payment in payments"
                        :key="payment.id"
                        class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-neutral-800"
                    >
                        <span>{{
                            payment.bank_account?.name ?? 'Sem conta'
                        }}</span>
                        <span>{{
                            payment.amount?.formatted ?? '---'
                        }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div v-else class="text-sm text-gray-500">
            Selecione uma despesa para iniciar a conciliação.
        </div>
    </div>
</template>

<style scoped></style>
