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
        v-model="filters[name]"
        :label="label"
        :options="options"
        :width="width"
    />
</template>
