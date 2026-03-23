<script setup>
import { inject, ref, onMounted } from 'vue';
import AppSelect from '@/components/base/AppSelect.vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps({
    label: String,
    name: String,
    selectedValue: Number,
    options: Array,
    width: String,
});

onMounted(() => {
    registerFilter?.(props.name, {
        label: 'com status',
        type: 'select',
        value: props.selectedValue,
        display: props.options.find((option) => option.value === props.selectedValue)?.label,
    });
});

const selectedItem = ref(null);
const handleSelected = (item) => {
    selectedItem.value = item;

    registerFilter?.(props.name, {
        label: 'com status',
        type: 'select',
        value: item.value,
        display: item.label,
    });
};
</script>

<template>
    <AppSelect
        class="min-w-[180px] flex-[1] md:max-w-[250px]"
        v-model="filters[name]"
        @selected="handleSelected"
        url="/payment-statuses"
        :label="label"
        :width="width"
    />
</template>
