<script setup lang="ts">
import { Pie } from 'vue-chartjs';

defineProps<{
    data: any;
    options?: any;
}>();
</script>

<template>
    <div class="relative h-[350px] w-full">
        <Pie :data="data" :options="options" />
    </div>
</template>
