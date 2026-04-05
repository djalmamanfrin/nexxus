<script setup lang="ts">
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import BarChart from '@/components/charts/BarChart.vue';
import LineChart from '@/components/charts/LineChart.vue';
import PieChart from '@/components/charts/PieChart.vue';
import { defaultOptions } from '@/lib/charts/options';
import { createDataset } from '@/lib/charts/dataset';

const props = defineProps<{
    expensesByWork: { name: string; total: number }[];
    expensesByCostCenter: { name: string; total: number }[];
    expensesByMonth: { month: string; total: number }[];
    totals: {
        expenses: number;
        payments: number;
        budget: number;
    };
}>();

// ============================
// FORMAT
// ============================

const money = (value: number) =>
    new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(value);

// ============================
// KPI
// ============================

const percentUsed = computed(() => {
    if (!props.totals.budget) return 0;
    return (props.totals.expenses / props.totals.budget) * 100;
});

// ============================
// CHARTS
// ============================

// BAR
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

// LINE (com gradiente)
const monthChart = computed(() => ({
    labels: props.expensesByMonth.map((i) => i.month),
    datasets: [
        {
            ...createDataset(
                'Despesas mensais',
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

// PIE
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
</script>

<template>
    <AppLayout>
        <div class="space-y-6">
            <!-- ================= KPI ================= -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Despesas
                    </p>
                    <h2
                        class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white"
                    >
                        {{ money(totals.expenses) }}
                    </h2>
                </div>

                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Pagamentos
                    </p>
                    <h2
                        class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white"
                    >
                        {{ money(totals.payments) }}
                    </h2>
                </div>

                <div
                    class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Orçamento
                    </p>
                    <h2
                        class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white"
                    >
                        {{ money(totals.budget) }}
                    </h2>
                </div>
            </div>

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

            <!-- ================= GRIDS ================= -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- BAR -->
                <div
                    class="h-[350px] rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="chart-title">Despesas por Obra</h3>
                    <BarChart :data="workChart" :options="defaultOptions" />
                </div>

                <!-- LINE -->
                <div
                    class="h-[350px] rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="chart-title">Evolução Mensal</h3>
                    <LineChart :data="monthChart" :options="defaultOptions" />
                </div>
            </div>

            <!-- ================= PIE ================= -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div
                    class="h-[350px] rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <h3 class="chart-title">
                        Distribuição por Centro de Custo
                    </h3>
                    <PieChart
                        :data="costCenterChart"
                        :options="defaultOptions"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
