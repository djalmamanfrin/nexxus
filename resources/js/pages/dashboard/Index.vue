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

const percentUsed = computed(() => {
    if (!props.totals.budget) return 0;
    return (props.totals.expenses / props.totals.budget) * 100;
});

const kpis = computed(() => [
    {
        title: 'Despesas',
        value: money(props.totals.expenses),
    },
    {
        title: 'Orçamentos',
        value: money(props.totals.budget),
    },
    {
        title: 'Burn Rate',
        value: money(props.totals.burn_rate),
    },
    {
        title: 'Previsão',
        value: `${props.totals.forecast_months.toFixed(1)} meses`,
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
            <div
                class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
                <div class="mb-2 flex justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        Uso do orçamento
                    </span>
                    <span class="text-sm font-medium">
                        {{ percentUsed.toFixed(1) }}%
                    </span>
                </div>

                <div
                    class="h-3 w-full rounded-full bg-gray-200 dark:bg-gray-700"
                >
                    <div
                        class="h-3 rounded-full bg-blue-500 transition-all"
                        :style="{ width: percentUsed + '%' }"
                    />
                </div>
            </div>
            <div class="my-4"></div>
            <!-- ================= GRIDS ================= -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- BAR -->
                <div
                    class="card rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="chart-title">Despesas por Obra</h3>
                    <BarChart
                        :data="workChart"
                        :options="{
                            ...defaultOptions,
                            onClick: handleWorkClick,
                        }"
                    />
                </div>

                <!-- ================= ORÇADO VS REAL ================= -->
                <div
                    class="card rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="mb-4">Orçado vs Real</h3>
                    <BarChart :data="budgetChart" :options="defaultOptions" />
                </div>
            </div>
            <div class="my-4"></div>

            <!-- LINE -->
            <div class="grid grid-cols-1 gap-6">
                <div
                    class="card rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="chart-title">Evolução Mensal</h3>
                    <LineChart :data="monthChart" :options="defaultOptions" />
                </div>
            </div>
            <div class="my-4"></div>

            <!-- ================= PIE ================= -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div
                    class="card rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="chart-title">
                        Distribuição por Centro de Custo
                    </h3>
                    <PieChart
                        :data="costCenterChart"
                        :options="defaultOptions"
                    />
                </div>
                <div
                    class="card rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="mb-4">Payees</h3>
                    <PieChart :data="payeeChart" :options="defaultOptions" />
                </div>
            </div>
            <div class="my-4"></div>
        </div>
    </AppLayout>
</template>
