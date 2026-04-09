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

// 🔥 REGISTRO REATIVO
watchEffect(() => {
    const value = filters[props.name];

    if (!value) {
        registerFilter?.(props.name, null);
        return;
    }

    const selected = props.options?.find(
        (opt) => String(opt.value) === String(value),
    );

    if (!selected) {
        registerFilter?.(props.name, null);
        return;
    }

    registerFilter?.(props.name, {
        label: props.label?.toLowerCase() + ' com',
        format: () => selected.label,
        clear: () => {
            filters[props.name] = null;
        },
    });
});
</script>

<template>
    <AppSelect
        class="min-w-[180px] flex-[1] md:max-w-[250px]"
        v-model="filters[name]"
        :options="options"
        :label="label"
        :width="width"
    />
</template>
