<script setup lang="ts">
import ChartCard from './ChartCard.vue';
import BarChart from '@/components/charts/BarChart.vue';

defineProps<{
    items: {
        title: string;
        data: any;
        options?: any;
    }[];
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <ChartCard
            v-for="(item, index) in items"
            :key="index"
            :title="item.title"
        >
            <BarChart :data="item.data" :options="item.options" />
        </ChartCard>
    </div>
    <div class="my-4"></div>
</template>
