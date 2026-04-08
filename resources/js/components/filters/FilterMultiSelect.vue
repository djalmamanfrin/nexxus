<script setup lang="ts">
import { SelectOption } from '@/types/select';
import { inject, onMounted, ref, watch } from 'vue';
import AppSelect from '@/components/base/AppSelect.vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps<{
    label?: string;
    name: string;
    options: SelectOption[];
    width?: string;
}>();

const selectedItems = ref<SelectOption[]>([]);

watch(
    () => filters[props.name],
    (values) => {
        if (!values?.length) {
            selectedItems.value = [];
            return;
        }

        selectedItems.value = props.options.filter((opt) =>
            values.includes(opt.value),
        );
    },
    { immediate: true },
);

const handleSelected = (items: SelectOption[]) => {
    selectedItems.value = items;

    filters[props.name] = items.map((item) => item.value);

    registerFilter?.(props.name, {
        label: 'em empresas',
        type: 'multi-select',
        value: items.map((i) => i.value),
        display: items.map((i) => i.label).join(', '),
    });
};

onMounted(() => {
    if (!filters[props.name]?.length) return;

    handleSelected(
        props.options.filter((opt) => filters[props.name].includes(opt.value)),
    );
});
</script>

<template>
    <AppSelect
        :multiple="true"
        :label="label"
        :options="options"
        :model-value="filters[name]"
        @update:modelValue="handleSelected"
        :width="width"
        :required="false"
    />
</template>
