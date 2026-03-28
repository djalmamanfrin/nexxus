<script setup lang="ts">
import { watch } from 'vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import { formatDateTime } from '@/lib/date';
import AppLabel from '@/components/base/AppLabel.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';
import CreateCostCenter from '@/pages/cost_centers/CreateCostCenter.vue';

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
    expense: Expense | null;
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
            payee_id: expense.payee?.id ?? '',
            cost_center_id: expense.cost_center?.id ?? '',
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
            <AppInput v-model="form.reference" label="Referencia" />
            <AppInput v-model="form.amount" label="Valor" mask="currency" />

            <AppSelect
                v-model="form.payee_id"
                url="payees/options"
                label="Beneficiário"
                name="payee_id"
            />

            <AppSelectWithModal
                v-model="form.cost_center_id"
                showCreate
                @created="handleCreated"
                :createComponent="CreateCostCenter"
                url="cost-centers"
                label="C. de Custo"
                name="cost_center_id"
                width="w-56"
                title="Novo C. de Custo"
                description="Como deseja nomear?"
            />

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
