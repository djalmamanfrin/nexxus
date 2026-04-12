<script setup lang="ts">
import type { Payment } from '@/types';
import Divider from '@/components/reconciliation/Divider.vue';
import Amount from '@/components/reconciliation/Amount.vue';
import FieldRow from '@/components/reconciliation/FieldRow.vue';
import Section from '@/components/reconciliation/Section.vue';

const props = defineProps<{
    payment: Payment;
    selected?: boolean;
}>();

const emit = defineEmits<{
    (e: 'select', payment: Payment): void;
}>();

function handleClick() {
    emit('select', props.payment);
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
        <Amount :amount="payment.amount?.formatted" />

        <Divider />
        <Section title="Sobre a transação">
            <FieldRow label="Data do pagamento" :value="payment.paid_at?.formatted" />
        </Section>
        <Divider />
        <Section title="Quem pagou">
            <FieldRow label="Nome" :value="payment.bank_account?.name" />
            <FieldRow label="CNPJ/CPF" :value="payment.bank_account?.document" mono />
            <FieldRow label="Agencia" :value="payment.bank_account?.agency" mono />
            <FieldRow label="Conta" :value="payment.bank_account?.account_number" />
        </Section>
    </div>
</template>
