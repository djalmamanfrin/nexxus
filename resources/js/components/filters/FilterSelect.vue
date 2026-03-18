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
        class="min-w-[180px] md:max-w-[250px] flex-[1]"
        v-model="filters[name]"
        :label="label"
        :options="options"
        :width="width"
    />
</template>
