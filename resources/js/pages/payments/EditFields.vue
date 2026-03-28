<script setup lang="ts">
import { watch } from 'vue';
import CreateStatus from '@/pages/payments/CreateStatus.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelectWithUpload from '@/components/base/AppSelectWithUpload.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import { formatDateTime } from '@/lib/date';
import { ArrowLeft, BankNote } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';
import Icon from '@/components/Icon.vue';
import AppLabel from '@/components/base/AppLabel.vue';
import { Payment } from '@/types';

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
            expense_id: payment.expense?.id ?? '',
            bank_account_id: payment.bank_account?.id ?? '',
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
            <AppSelect
                v-model="form.bank_account_id"
                url="bank-accounts"
                label="Conta Bancária"
                name="bank_id"
            />

            <AppSelectWithUpload
                v-model="form.expense_id"
                @created="handleCreated"
                :url="`/payments/${payment.id}/available-expenses`"
                label="Despesa"
                name="expense_id"
                width="w-56"
            />

            <!--            <AppSelectWithModal-->
            <!--                v-model="form.payment_status_id"-->
            <!--                showCreate-->
            <!--                @created="handleCreated"-->
            <!--                :createComponent="CreateStatus"-->
            <!--                url="payment-statuses"-->
            <!--                label="Status"-->
            <!--                name="status"-->
            <!--                width="w-56"-->
            <!--                title="Novo status"-->
            <!--                description="Como deseja nomear?"-->
            <!--            />-->

            <!--            <div>-->
            <!--                <label class="form-label">Tipo</label>-->
            <!--                <input v-model="form.payment_type_id" class="form-input" />-->
            <!--            </div>-->

            <AppInput v-model="form.amount" label="Valor" mask="currency" />

            <AppInput
                v-model="form.paid_at"
                label="Pago em"
                type="datetime-local"
            />
        </div>
        <div class="flex items-center justify-between pt-2">
            <AppLabel label="Criado em:" />
            <p class="text-sm text-gray-500">
                {{ formatDateTime(payment.created_at) }}
            </p>
        </div>
    </div>
</template>
