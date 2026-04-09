<script setup lang="ts">
import AppSelect from '@/components/base/AppSelect.vue';
import { SelectOption } from '@/types/select';
import { inject, watchEffect } from 'vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

defineProps<{
    label?: string;
    name: string;
    options: SelectOption[];
    width?: string;
}>();

watchEffect(() => {
    const values = filters[name];
    console.log(values);

    if (!values || !values.length) {
        registerFilter?.(name, null);
        return;
    }

    const selectedItems = options.filter((opt) =>
        values.includes(opt.value),
    );

    if (!selectedItems.length) {
        registerFilter?.(name, null);
        return;
    }

    console.log(selectedItems);

    registerFilter?.(name, {
        label: label.toLowerCase() + 'com o nome',
        format: () => selectedItems.map((i) => i.label).join(', '),
        clear: () => {
            filters[name] = [];
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
