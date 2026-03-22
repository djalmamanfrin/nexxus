<script setup>
import { inject, onMounted } from 'vue';
import AppSelect from '@/components/base/AppSelect.vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps({
    label: String,
    name: String,
    options: Array,
    width: String,
});

onMounted(() => {
    registerFilter?.(props.name, {
        label: 'com status',
        type: 'select',
        options: props.options,
    });
});
</script>

<template>
    <AppSelect
        class="min-w-[180px] flex-[1] md:max-w-[250px]"
        v-model="filters[name]"
        url="/payment-statuses"
        :label="label"
        :width="width"
    />
</template>
