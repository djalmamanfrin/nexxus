<script setup lang="ts">
import { computed } from 'vue';
import type { Expense, Payment } from '@/types';
import AppButton from '@/components/AppButton.vue';
import { useForm } from '@inertiajs/vue3';
import FieldRow from '@/components/reconciliation/FieldRow.vue';
import Divider from '@/components/reconciliation/Divider.vue';
import Section from '@/components/reconciliation/Section.vue';

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

const statusColor = computed(() => {
    if (!props.expense) return 'gray';
    if (totalPayments.value === 0) return 'yellow';
    if (totalPayments.value < expenseAmount.value) return 'yellow';
    if (totalPayments.value === expenseAmount.value) return 'green';
    return 'red';
});
</script>

<template>
    <div
        class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
    >
        <div v-if="expense" class="space-y-3">
            <div class="flex flex-col items-center text-center">
                <h3 class="text-lg font-semibold uppercase"></h3>
            </div>
            <Section>
                <FieldRow label="Status" :value="status" :color="statusColor" />
            </Section>
            <Divider />
            <Section>
                <FieldRow label="Valor da despesa" :value="expense.amount?.formatted" />
                <FieldRow
                    label="Referência"
                    :value="expense.reference ?? 'Sem referência'"
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
