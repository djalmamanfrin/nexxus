<script setup lang="ts">
import type { Expense } from '@/types';
import Divider from '@/components/reconciliation/Divider.vue';
import Amount from '@/components/reconciliation/Amount.vue';
import FieldRow from '@/components/reconciliation/FieldRow.vue';
import Section from '@/components/reconciliation/Section.vue';

const props = defineProps<{
    expense: Expense;
    selected?: boolean;
}>();

const emit = defineEmits<{
    (e: 'select', expense: Expense): void;
}>();

function handleClick() {
    emit('select', props.expense);
}
</script>

<template>
    <div
        @click="handleClick"
        class="group relative mb-4 cursor-pointer rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-[1px] hover:shadow-md dark:border-neutral-800 dark:bg-neutral-900"
        :class="{
            'border-blue-500 shadow-md ring-2 ring-blue-100 dark:ring-blue-900/40':
                selected,
        }"
    >
        <Amount :amount="expense.amount?.formatted" />

        <Divider />

        <Section title="Sobre a transação">
            <FieldRow label="ID" :value="`#${expense.id}`" />
            <FieldRow label="Vencimento" :value="expense.due_at?.formatted" />
            <FieldRow
                label="Competência"
                :value="expense.competence_date?.formatted"
            />
            <FieldRow
                label="Status"
                :value="expense.status?.name"
                :color="expense.status?.color"
            />
        </Section>
        <Divider />
        <Section title="Quem recebeu">
            <FieldRow label="Nome" :value="expense.payee?.name" />
            <FieldRow label="CNPJ/CPF" :value="expense.payee?.document" mono />
        </Section>
    </div>
</template>
