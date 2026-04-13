<script setup lang="ts">
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import FilterText from '@/components/filters/FilterText.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import { useFilters } from '@/composables/useFilters';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Expense, Payment } from '@/types';
import { computed, ref } from 'vue';
import ExpenseCard from '@/components/reconciliation/ExpenseCard.vue';
import ColumnSection from '@/components/reconciliation/ColumnSection.vue';
import PaymentCard from '@/components/reconciliation/PaymentCard.vue';
import ReconciliationSummary from '@/components/reconciliation/ReconciliationSummary.vue';
import AppButton from '@/components/AppButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
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

const selectedExpense = ref<Expense | null>(null);

const linkedPayments = ref<Payment[]>([]);

function selectExpense(expense: Expense) {
    selectedExpense.value = expense;
    linkedPayments.value = [];
    form.expense_id = expense.id;
    form.payments = [];
}

function addPayment(payment: Payment) {
    if (!selectedExpense.value) return;

    const index = linkedPayments.value.findIndex((p) => p.id === payment.id);

    if (index !== -1) {
        linkedPayments.value.splice(index, 1);
        return;
    }

    linkedPayments.value.push(payment);
    form.payments.push({ id: payment.id, amount: payment.amount?.value ?? 0 });
}

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
    },
    '/consiliation',
);

const emit = defineEmits(['update:filters']);
const filtersProxy = computed({
    get: () => filters,
    set: (value) => emit('update:filters', value),
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Despesas',
        href: '',
    },
];

const form = useForm({
    expense_id: null as number | null,
    payments: [] as { id: number; amount: number }[],
});

function submit() {
    form.post('/reconciliations', {
        onSuccess: () => {
            form.reset();
            selectedExpense.value = null;
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
                    :disabled="!selectedExpense || !linkedPayments.length"
                />
            </form>
        </template>
        <div class="content-box">
            <FlashMessage />

            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filtersProxy"
                    @change="search"
                    @clear="clear"
                >
                    <FilterText
                        label="Buscar despesa"
                        name="search_by"
                        placeholder="CPF, CNPJ ou texto"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <div
                class="grid grid-cols-1 gap-8 p-4 md:grid-cols-2 xl:grid-cols-3"
            >
                <ColumnSection title="Despesas">
                    <ExpenseCard
                        v-for="exp in expenses.data"
                        :key="exp.id"
                        :expense="exp"
                        :selected="selectedExpense?.id === exp.id"
                        @select="selectExpense"
                    />
                </ColumnSection>
                <ColumnSection title="Conciliação">
                    <ReconciliationSummary
                        :expense="selectedExpense"
                        :payments="linkedPayments"
                    />
                </ColumnSection>
                <ColumnSection title="Pagamentos">
                    <PaymentCard
                        v-for="pay in payments.data"
                        :key="pay.id"
                        :payment="pay"
                        :selected="linkedPayments?.some((p) => p.id === pay.id)"
                        @select="addPayment"
                    />
                </ColumnSection>
            </div>
        </div>
    </AppLayout>
</template>
