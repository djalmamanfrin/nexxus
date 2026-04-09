<script setup lang="ts">
import AppSelect from '@/components/base/AppSelect.vue';
import { SelectOption } from '@/types/select';
import { inject, watchEffect } from 'vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps<{
    label?: string;
    name: string;
    options: SelectOption[];
    width?: string;
}>();

watchEffect(() => {
    const values = filters[props.name];

    if (!values || !values.length) {
        registerFilter?.(props.name, null);
        return;
    }

    const selectedItems = props.options.filter((opt) =>
        values.includes(opt.value),
    );

    if (!selectedItems.length) {
        registerFilter?.(props.name, null);
        return;
    }

    registerFilter?.(props.name, {
        label: props.label.toLowerCase() + 'com o nome',
        format: () => selectedItems.map((i) => i.label).join(', '),
        clear: () => {
            filters[props.name] = [];
        },
    });
});
</script>

<template>
    <AppSelect
        :multiple="true"
        v-model="filters[name]"
        :options="options"
        :label="label"
        :width="width"
        :required="false"
        class="min-w-[280px] md:max-w-[500px] md:flex-[2]"
    />
</template>
