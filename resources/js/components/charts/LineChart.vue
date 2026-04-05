<script setup lang="ts">
import { Line } from 'vue-chartjs';

defineProps<{
    data: any;
    options?: any;
}>();
</script>

<template>
    <Line
        :data="{
            ...data,
            datasets: data.datasets.map((ds) => ({
                tension: 0.4, // curva suave
                fill: true, // área abaixo da linha
                pointRadius: 3,
                pointHoverRadius: 5,
                ...ds,
            })),
        }"
        :options="options"
    />
</template>
