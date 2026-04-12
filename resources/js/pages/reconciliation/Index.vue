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
}

function addPayment(payment: Payment) {
    if (!selectedExpense.value) return;

    const index = linkedPayments.value.findIndex((p) => p.id === payment.id);

    if (index !== -1) {
        linkedPayments.value.splice(index, 1);
        return;
    }

    linkedPayments.value.push(payment);
}

const totalLinked = computed(() =>
    linkedPayments.value.reduce((acc, p) => acc + Number(p?.amount?.value), 0),
);

const selectedExpenseAmount = computed(
    () => Number(selectedExpense.value?.amount?.value) ?? 0,
);

const isOverflow = computed(() => {
    if (!selectedExpenseAmount) return false;

    return totalLinked.value > selectedExpenseAmount.value;
});

const status = computed(() => {
    if (!selectedExpenseAmount) return '';

    if (totalLinked.value === 0) return 'Pendente';
    if (totalLinked.value < selectedExpenseAmount.value) return 'Parcial';
    if (totalLinked.value === selectedExpenseAmount.value) return 'Conciliado';
    return 'Excedente';
});

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
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
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
