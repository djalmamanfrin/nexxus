<script setup lang="ts">
import { inject, ref, onMounted } from 'vue';
import AppSelect from '@/components/base/AppSelect.vue';
import { SelectOption } from '@/types/select';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps<{
    label?: string;
    name: string;
    selectedValue?: string | null;
    options: SelectOption[];
    width?: string;
}>();

onMounted(() => {
    if (props.selectedValue == null || !props.options?.length) return;

    const selected = props.options.find(
        (option) => option.value === Number(props.selectedValue),
    );

    if (!selected) return;

    registerFilter?.(props.name, {
        label: 'com status',
        type: 'select',
        value: selected.value,
        display: selected.label,
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
