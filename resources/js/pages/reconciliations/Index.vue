<script setup lang="ts">
import FlashMessage from '@/components/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Expense, Payment, Reconciliation } from '@/types';
import { computed, ref } from 'vue';
import ExpenseCard from '@/components/reconciliation/ExpenseCard.vue';
import ColumnSection from '@/components/reconciliation/ColumnSection.vue';
import PaymentCard from '@/components/reconciliation/PaymentCard.vue';
import ReconciliationSummary from '@/components/reconciliation/ReconciliationSummary.vue';
import AppButton from '@/components/AppButton.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

defineProps<{
    payments: {
        data: Payment[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    expenses: {
        data: Expense[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const selectedExpenses = ref<Expense[]>([]);
const linkedPayments = ref<Payment[]>([]);
const expensePartials = ref<Reconciliation[]>([]);

async function getExpensePartials(expenseId: number) {
    try {
        let response = await axios.get<Reconciliation[]>(
            `/reconciliations/${expenseId}/partials`,
        );
        expensePartials.value = response.data;
    } catch (e) {
        console.error(e);
    } finally {
    }
}

function toggleExpense(expense: Expense) {
    const index = selectedExpenses.value.findIndex(
        (item) => item.id === expense.id,
    );
    if (index !== -1) {
        selectedExpenses.value.splice(index, 1);
        form.expenses.splice(index, 1);
        return;
    }
    getExpensePartials(expense.id);
    selectedExpenses.value.push(expense);
    form.expenses.push({ id: expense.id, amount: expense.amount?.value ?? 0 });
}

function togglePayment(payment: Payment) {
    const index = linkedPayments.value.findIndex(
        (item) => item.id === payment.id,
    );
    if (index !== -1) {
        linkedPayments.value.splice(index, 1);
        form.payments.splice(index, 1);
        return;
    }
    linkedPayments.value.push(payment);
    form.payments.push({ id: payment.id, amount: payment.amount?.value ?? 0 });
}

const totalExpenseAmount = computed(() =>
    selectedExpenses.value.reduce((total, expense) => {
        return total + (expense.amount?.value ?? 0);
    }, 0),
);

const totalPayments = computed(() =>
    linkedPayments.value.reduce((total, payment) => {
        return total + (payment.amount?.value ?? 0);
    }, 0),
);

const difference = computed(
    () => totalPayments.value - totalExpenseAmount.value,
);

const canSave = computed(() => {
    return (
        selectedExpenses.value.length > 0 &&
        linkedPayments.value.length > 0 &&
        difference.value <= 0
    );
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Despesas',
        href: '',
    },
];

const form = useForm({
    expenses: [] as { id: number; amount: number }[],
    payments: [] as { id: number; amount: number }[],
});

function submit() {
    form.post('/reconciliations', {
        onSuccess: () => {
            form.reset();
            selectedExpenses.value = [];
            linkedPayments.value = [];
        },
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <form @submit.prevent="submit">
                <AppButton
                    type="submit"
                    label="Salvar conciliação"
                    variant="success"
                    :disabled="!canSave"
                    :class="canSave ? '' : 'cursor-not-allowed opacity-50'"
                />
            </form>
        </template>
        <div class="content-box">
            <FlashMessage />
            <div class="my-4"></div>
            <div
                class="grid grid-cols-1 gap-8 p-4 md:grid-cols-2 xl:grid-cols-3"
            >
                <ColumnSection title="Despesas">
                    <ExpenseCard
                        v-for="exp in expenses.data"
                        :key="exp.id"
                        :expense="exp"
                        :selected="
                            selectedExpenses.some((item) => item.id === exp.id)
                        "
                        @select="toggleExpense"
                    />
                </ColumnSection>

                <ColumnSection title="Conciliação">
                    <ReconciliationSummary
                        :expenses="selectedExpenses"
                        :expensePartials="expensePartials"
                        :payments="linkedPayments"
                    />
                </ColumnSection>

                <ColumnSection title="Pagamentos">
                    <PaymentCard
                        v-for="pay in payments.data"
                        :key="pay.id"
                        :payment="pay"
                        :selected="
                            linkedPayments.some((item) => item.id === pay.id)
                        "
                        @select="togglePayment"
                    />
                </ColumnSection>
            </div>
        </div>
    </AppLayout>
</template>
