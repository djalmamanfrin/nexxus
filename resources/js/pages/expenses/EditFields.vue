<script setup lang="ts">
import { watch } from 'vue';
import CreateStatus from '@/pages/expenses/CreateStatus.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelectWithUpload from '@/components/base/AppSelectWithUpload.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import { formatDateTime } from '@/lib/date';
import { ArrowLeft, BankNote } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';
import Icon from '@/components/Icon.vue';
import AppLabel from '@/components/base/AppLabel.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';

interface Expense {
    id: number;
    reference: string;
    amount: string;
    payee_id: string;
    cost_center_id: string;
    expense_status_id: string;
    expense_category_id: string;
    due_at: string;
    competence_date: string;
}

interface FormType {
    id: number;
    reference: string;
    amount: string;
    payee_id: string;
    cost_center_id: string;
    expense_status_id: string;
    expense_category_id: string;
    due_at: string;
    competence_date: string;
    attachment: File | null;
}

const props = defineProps<{
    expense: Expense;
    form: FormType & {
        defaults: Function;
        reset: Function;
    };
}>();

watch(
    () => props.expense,
    (expense) => {
        if (!expense) return;

        props.form.defaults({
            reference: expense.reference ?? '',
            amount: expense.amount ?? '',
            payee_id: expense.payee_id ?? '',
            cost_center_id: expense.cost_center_id ?? '',
            expense_status_id: expense.expense_status_id ?? '',
            expense_category_id: expense.expense_category_id ?? '',
            due_at: expense.due_at ?? '',
            competence_date: expense.competence_date ?? '',
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
    <div v-if="expense" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <AppSelect
                v-model="form.payee_id"
                url="payees"
                label="Beneficiário"
                name="payee_id"
            />

            <!--            <AppSelectWithModal-->
            <!--                v-model="form.cost_center_id"-->
            <!--                showCreate-->
            <!--                @created="handleCreated"-->
            <!--                :createComponent="CreateCostCenter" falta criar o componente-->
            <!--                url="cost-centers"-->
            <!--                label="C. de Custo"-->
            <!--                name="cost_center_id"-->
            <!--                width="w-56"-->
            <!--                title="Novo C. de Custo"-->
            <!--                description="Como deseja nomear?"-->
            <!--            />-->

            <AppInput v-model="form.reference" label="Referencia" />
            <AppInput v-model="form.amount" label="Valor" mask="currency" />

            <AppInput
                v-model="form.due_at"
                label="Vencimento em"
                type="datetime-local"
            />
            <AppInput
                v-model="form.competence_date"
                label="Competencia"
                type="datetime-local"
            />
        </div>
        <div class="flex items-center justify-between pt-2">
            <AppLabel label="Criado em:" />
            <p class="text-sm text-gray-500">
                {{ formatDateTime(expense.created_at) }}
            </p>
        </div>
    </div>
</template>
