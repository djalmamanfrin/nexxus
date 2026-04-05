<script setup lang="ts">
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';
import PieChart from '@/components/charts/PieChart.vue';
import { defaultOptions } from '@/lib/charts/options';
import { createDataset } from '@/lib/charts/dataset';
import KpiGrid from '@/components/charts/KpiGrid.vue';
import BudgetProgress from '@/components/charts/BudgetProgress.vue';
import ChartGrid from '@/components/charts/ChartGrid.vue';

const props = defineProps<{
    filters: any;
    expensesByWork: any[];
    budgetVsReal: any[];
    expensesByCostCenter: any[];
    expensesByMonth: any[];
    expensesByPayee: any[];
    totals: any;
}>();

const money = (v: number) =>
    new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(v);

// ================= FILTER =================

const applyFilter = (filters) => {
    router.get('/dashboard', filters, {
        preserveState: true,
        replace: true,
    });
};

// ================= CLICK CHART =================

const handleWorkClick = (_, elements) => {
    if (!elements.length) return;

    const index = elements[0].index;
    const work = props.expensesByWork[index];

    applyFilter({
        ...props.filters,
        work_id: work.id,
    });
};

// ================= CHARTS =================

const workChart = computed(() => ({
    labels: props.expensesByWork.map((i) => i.name),
    datasets: [
        createDataset(
            'Despesas',
            props.expensesByWork.map((i) => i.total),
            'primary',
        ),
    ],
}));

const budgetChart = computed(() => ({
    labels: props.budgetVsReal.map((i) => i.name),
    datasets: [
        createDataset(
            'Orçado',
            props.budgetVsReal.map((i) => i.budget),
            'warning',
        ),
        createDataset(
            'Real',
            props.budgetVsReal.map((i) => i.expenses),
            'danger',
        ),
    ],
}));

const monthChart = computed(() => ({
    labels: props.expensesByMonth.map((i) => i.month),
    datasets: [
        {
            ...createDataset(
                'Mensal',
                props.expensesByMonth.map((i) => i.total),
                'success',
            ),
            fill: true,
            tension: 0.4,
            backgroundColor: (ctx) => {
                const { chart } = ctx;
                const { ctx: canvas, chartArea } = chart;

                if (!chartArea) return;

                const gradient = canvas.createLinearGradient(
                    0,
                    chartArea.top,
                    0,
                    chartArea.bottom,
                );

                gradient.addColorStop(0, 'rgba(16,185,129,0.7)');
                gradient.addColorStop(1, 'rgba(16,185,129,0.05)');

                return gradient;
            },
        },
    ],
}));

const costCenterChart = computed(() => ({
    labels: props.expensesByCostCenter.map((i) => i.name),
    datasets: [
        {
            data: props.expensesByCostCenter.map((i) => i.total),
            backgroundColor: [
                '#3B82F6',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#8B5CF6',
                '#06B6D4',
            ],
        },
    ],
}));

const payeeChart = computed(() => ({
    labels: props.expensesByPayee.map((i) => i.name),
    datasets: [
        {
            data: props.expensesByPayee.map((i) => i.total),
            backgroundColor: [
                '#3B82F6',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#8B5CF6',
                '#06B6D4',
            ],
        },
    ],
}));

const kpis = computed(() => [
    {
        title: 'Despesas',
        value: money(props.totals.expenses),
        tooltip:
            'Total de despesas registradas no período selecionado. ' +
            'Representa o valor já comprometido.',
    },
    {
        title: 'Orçamentos',
        value: money(props.totals.budget),
        tooltip:
            'Soma dos orçamentos definidos para os centros de custo. ' +
            'Usado como limite planejado de gastos.',
    },
    {
        title: 'Gasto médio por período',
        value: money(props.totals.burn_rate),
        tooltip:
            'Valor médio de despesas por mês. ' +
            'Indica a velocidade de consumo do orçamento.',
    },
    {
        title: 'Previsão',
        value: `${props.totals.forecast_months.toFixed(1)} meses`,
        tooltip:
            'Estimativa de quantos meses o orçamento atual ainda suporta, ' +
            'com base no ritmo médio de gastos (burn rate).',
    },
]);

const budget = computed(() => props.totals.budget || 0);
const percentExpenses = computed(() => {
    if (!budget.value) return 0;
    return (props.totals.expenses / budget.value) * 100;
});
const percentPayments = computed(() => {
    if (!budget.value) return 0;
    return (props.totals.payments / budget.value) * 100;
});

const budgetProgress = computed(() => [
    {
        label: 'Despesas',
        value: percentExpenses.value,
        color: 'bg-blue-500',
    },
    {
        label: 'Pagamentos',
        value: percentPayments.value,
        color: 'bg-green-500',
    },
]);

const charts = computed(() => [
    // ================= BAR =================
    {
        title: 'Despesas por Obra',
        component: BarChart,
        data: workChart.value,
        options: {
            ...defaultOptions,
            onClick: handleWorkClick,
        },
        icon: 'info',
        tooltip:
            'Apresenta o total de despesas agrupadas por obra no período ' +
            'selecionado. Permite identificar quais projetos consomem mais ' +
            'recursos e priorizar análises.',
    },
    {
        title: 'Orçado vs Real',
        component: BarChart,
        data: budgetChart.value,
        options: defaultOptions,
        icon: 'info',
        tooltip:
            'Apresenta o total de despesas agrupadas por obra no período ' +
            'selecionado. Permite identificar quais projetos consomem mais ' +
            'recursos e priorizar análises.',
    },
    // ================= LINE =================
    {
        title: 'Evolução Mensal',
        component: LineChart,
        data: monthChart.value,
        options: defaultOptions,
        cols: 'lg:col-span-2',
        icon: 'info',
        tooltip:
            'Compara o valor orçado com o valor efetivamente gasto por obra. ' +
            'Diferenças indicam desvios do planejamento e ajudam a identificar ' +
            'excessos ou economias.',
    },
    // ================= PIE =================
    {
        title: 'Distribuição por Centro de Custo',
        component: PieChart,
        data: costCenterChart.value,
        options: defaultOptions,
        icon: 'info',
        tooltip:
            'Mostra a evolução das despesas ao longo do tempo, com base na ' +
            'data de competência. Ajuda a identificar tendências, picos de ' +
            'gasto e mudanças no comportamento financeiro.',
    },
    {
        title: 'Parceiros',
        component: PieChart,
        data: payeeChart.value,
        options: defaultOptions,
        icon: 'info',
        tooltip:
            'Apresenta a distribuição de despesas por parceiro ou fornecedor. ' +
            'Ajuda a identificar concentração de gastos e principais responsáveis pelos custos.',
    },
]);
</script>

<template>
    <Head title="Obras" />
    <AppLayout>
        <div class="content-box">
            <!-- ================= FILTER ================= -->
            <div class="flex gap-3">
                <input type="date" v-model="filters.start_date" class="input" />
                <input type="date" v-model="filters.end_date" class="input" />
                <button @click="applyFilter(filters)" class="btn">
                    Filtrar
                </button>
            </div>
            <div class="my-4"></div>
            <!-- ================= KPI ================= -->
            <KpiGrid :items="kpis" />
            <!-- ================= PROGRESS ================= -->
            <BudgetProgress
                title="Uso do Orçamento"
                :items="budgetProgress"
                icon="info"
                tooltip="Compara despesas e pagamentos com o orçamento total.
                    Ajuda a entender quanto já foi consumido e o quanto ainda
                    está disponível."
            />
            <!-- ================= GRIDS ================= -->
            <ChartGrid :items="charts" />
        </div>
    </AppLayout>
</template>
