<script setup lang="ts">
import { watch } from 'vue';
import CreateStatus from '@/pages/payments/CreateStatus.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';

interface Payment {
    id: number;
    expense_id: string;
    bank_account_id: string;
    payment_status_id: string;
    payment_type_id: string;
    amount: string;
    paid_at: string;
}

interface FormType {
    expense_id: string;
    bank_account_id: string;
    payment_status_id: string;
    payment_type_id: string;
    amount: string;
    paid_at: string;
    attachment: File | null;
}

const props = defineProps<{
    payment: Payment;
    form: FormType & {
        defaults: Function;
        reset: Function;
    };
}>();

watch(
    () => props.payment,
    (payment) => {
        if (!payment) return;

        props.form.defaults({
            expense_id: payment.expense_id ?? '',
            bank_account_id: payment.bank_account_id ?? '',
            payment_status_id: payment.payment_status_id ?? '',
            payment_type_id: payment.payment_type_id ?? '',
            amount: payment.amount ?? '',
            paid_at: payment.paid_at ?? '',
        });

        props.form.reset();
    },
    { immediate: true },
);

const handleCreated = (item) => {
    props.form[item.field] = item.value;
};
</script>

<template>
    <div v-if="payment" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="form-label">Conta</label>
                <input v-model="form.bank_account_id" class="form-input" />
            </div>

            <div>
                <label class="form-label">Despesa</label>
                <input v-model="form.expense_id" class="form-input" />
            </div>

            <AppSelectWithModal
                v-model="form.payment_status_id"
                showCreate
                @created="handleCreated"
                :createComponent="CreateStatus"
                url="payment-statuses"
                label="Status"
                name="status"
                width="w-56"
                title="Novo status"
                description="Como deseja nomear?"
            />

            <div>
                <label class="form-label">Tipo</label>
                <input v-model="form.payment_type_id" class="form-input" />
            </div>

            <AppInput v-model="form.amount" label="Valor" mask="currency" />

            <AppInput
                v-model="form.paid_at"
                label="Pago em"
                type="datetime-local"
            />
        </div>
    </div>
</template>
