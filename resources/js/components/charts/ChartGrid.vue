<script setup lang="ts">
import ChartCard from './ChartCard.vue';

defineProps<{
    items: {
        title: string;
        component: any;
        data: any;
        options?: any;
        cols?: string;
    }[];
}>();
</script>

<template>
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <ChartCard
            v-for="(item, index) in items"
            :key="index"
            :title="item.title"
            :class="item.cols"
        >
            <component
                :is="item.component"
                :data="item.data"
                :options="item.options"
            />
        </ChartCard>
    </div>
    <div class="my-4"></div>
</template>
