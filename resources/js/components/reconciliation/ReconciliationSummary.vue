<script setup lang="ts">
import { computed } from 'vue';
import type { Expense, Payment, Reconciliation } from '@/types';
import AppButton from '@/components/AppButton.vue';
import { useForm } from '@inertiajs/vue3';
import FieldRow from '@/components/reconciliation/FieldRow.vue';
import Divider from '@/components/reconciliation/Divider.vue';
import Section from '@/components/reconciliation/Section.vue';
import reconciliations from '@/routes/reconciliations';

const props = defineProps<{
    expenses: Expense[];
    expensePartials: Reconciliation[];
    payments: Payment[];
}>();

const totalExpenseAmount = computed(() =>
    props.expenses.reduce((total, expense) => {
        return total + (expense.amount?.value ?? 0);
    }, 0),
);

const totalExpensePartials = computed(() => {
    return props.expensePartials.reduce((total, reconciliation) => {
        return total + (reconciliation.amount?.value ?? 0);
    }, 0);
});

const totalPayments = computed(() => {
    let totalPayments = props.payments.reduce((total, payment) => {
        return total + (payment.amount?.value ?? 0);
    }, 0);
    return totalPayments + totalExpensePartials.value;
});

const difference = computed(
    () => totalPayments.value - totalExpenseAmount.value,
);

const status = computed(() => {
    if (!props.expenses.length) return 'Nenhuma despesa selecionada';
    if (totalPayments.value === 0) return 'Pendente';
    if (totalPayments.value < totalExpenseAmount.value) return 'Parcial';
    if (totalPayments.value === totalExpenseAmount.value) return 'Conciliado';
    return 'Excedente';
});

const statusColor = computed(() => {
    if (!props.expenses.length) return 'gray';
    if (totalPayments.value === 0) return 'yellow';
    if (totalPayments.value < totalExpenseAmount.value) return 'yellow';
    if (totalPayments.value === totalExpenseAmount.value) return 'green';
    return 'red';
});
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
    >
        <div v-if="expenses.length" class="space-y-3">
            <div class="flex flex-col items-center text-center">
                <h3 class="text-lg font-semibold uppercase"></h3>
            </div>
            <Section>
                <FieldRow label="Status" :value="status" :color="statusColor" />
            </Section>
            <Divider />
            <Section>
                <FieldRow
                    label="Total de despesas"
                    :value="
                        totalExpenseAmount.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                        })
                    "
                />
            </Section>
            <Divider />
            <Section>
                <FieldRow
                    label="Total dos pagamentos"
                    :value="
                        totalPayments.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                        })
                    "
                    :color="statusColor"
                />

                <FieldRow
                    label="Diferença"
                    :value="
                        difference.toLocaleString('pt-BR', {
                            style: 'currency',
                            currency: 'BRL',
                        })
                    "
                    :color="statusColor"
                />
            </Section>

            <div v-if="expenses.length" class="mt-4">
                <h4 class="mb-2 text-sm font-semibold text-gray-600">
                    Despesas selecionadas
                </h4>
                <ul class="space-y-2">
                    <li
                        v-for="expense in expenses"
                        :key="expense.id"
                        class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-neutral-800"
                    >
                        <span>
                            {{`ID #${expense.id}`}}
                        </span>
                        <span>{{ expense.amount?.formatted ?? '---' }}</span>
                    </li>
                </ul>
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
                        <span>
                            {{`ID #${payment.id}`}}
                        </span>
                        <span>
                            {{payment.bank_account?.name ?? 'Sem conta' }}
                        </span>
                        <span>{{ payment.amount?.formatted ?? '---' }}</span>
                    </li>
                </ul>
            </div>
            <Divider />
            <div v-if="expensePartials.length" class="mt-4">
                <h4 class="mb-2 text-sm font-semibold text-gray-600">
                    Pagamentos já conciliados
                </h4>
                <ul class="space-y-2">
                    <li
                        v-for="payment in expensePartials"
                        :key="payment.id"
                        class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-neutral-800"
                    >
                        <span> Valor </span>
                        <span>{{ payment.amount?.formatted ?? '---' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div v-else class="flex flex-col items-center text-sm text-gray-500">
            Selecione uma despesa para iniciar a conciliação.
        </div>
    </div>
</template>

<style scoped></style>
