<script setup lang="ts">
import { Bar } from 'vue-chartjs';

defineProps<{
    data: any;
    options?: any;
}>();
</script>

<template>
    <div class="relative h-[350px] w-full">
        <Bar :data="data" :options="options" />
    </div>
</template>
