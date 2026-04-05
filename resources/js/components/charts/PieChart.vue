<script setup lang="ts">
import { Pie } from 'vue-chartjs';

defineProps<{
    data: any;
    options?: any;
}>();
</script>

<template>
    <Pie :data="data" :options="options" />
</template>
