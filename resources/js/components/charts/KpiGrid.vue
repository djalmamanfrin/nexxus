<script setup lang="ts">
import KpiCard from './KpiCard.vue';

defineProps<{
    items: {
        title: string;
        value: string | number;
    }[];
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <KpiCard
            v-for="(item, index) in items"
            :key="index"
            :title="item.title"
            :value="item.value"
        />
    </div>
    <div class="my-4"></div>
</template>
