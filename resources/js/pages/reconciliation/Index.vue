<script setup lang="ts">
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import FilterText from '@/components/filters/FilterText.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import { useFilters } from '@/composables/useFilters';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Expense, Payment } from '@/types';
import { computed, ref } from 'vue';
import ExpenseCard from '@/components/reconciliation/ExpenseCard.vue';

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

const linkedPayments = ref<{ payment: Payment; amount: number }[]>([]);

function selectExpense(expense: Expense) {
    selectedExpense.value = expense;
    linkedPayments.value = [];
}

function addPayment(payment: Payment) {
    if (!selectedExpense.value) return;

    // 🚫 evita duplicidade
    if (linkedPayments.value.some((p) => p.payment.id === payment.id)) {
        return;
    }

    linkedPayments.value.push({
        payment,
        amount: payment.amount,
    });
}

const totalLinked = computed(() =>
    linkedPayments.value.reduce((acc, p) => acc + Number(p.amount), 0),
);

const isOverflow = computed(() => {
    return totalLinked.value > (selectedExpense.value?.amount || 0);
});

const status = computed(() => {
    if (!selectedExpense.value) return '';

    if (totalLinked.value === 0) return 'Pendente';
    if (totalLinked.value < selectedExpense.value.amount) return 'Parcial';
    if (totalLinked.value === selectedExpense.value.amount) return 'Conciliado';
    return 'Excedente';
});

function removePayment(index: number) {
    linkedPayments.value.splice(index, 1);
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
                    <FilterTabs
                        v-if="statuses?.length"
                        label="Status"
                        name="status"
                        :tabs="statuses"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <div class="grid grid-cols-3 gap-4 p-4">
                <div class="flex flex-col">
                    <ExpenseCard
                        v-for="exp in expenses.data"
                        :key="exp.id"
                        :expense="exp"
                        :selected="selectedExpense?.id === exp.id"
                        @select="selectExpense"
                    />
                    <div
                        v-for="pay in payments.data"
                        :key="pay.id"
                        class="mb-2 rounded border p-2 hover:bg-gray-100"
                    >
                        {{ pay.bank_account?.name }} -
                        {{ pay.amount.formatted }}
                    </div>
                    <div class="rounded border p-3"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
