<script setup>
import { debounce } from 'lodash';
import { inject, ref, watch, onUnmounted, watchEffect } from 'vue';
import AppInput from '@/components/base/AppInput.vue';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const props = defineProps({
    label: String,
    name: String,
    placeholder: String,
    debounce: {
        default: 400,
    },
    icon: Function,
    width: {
        default: 'w-96',
    },
});

const localValue = ref(filters[props.name] || '');

// 🔹 debounce → atualiza filtro global
const updateFilter = debounce((value) => {
    filters[props.name] = value;
}, props.debounce);

watch(localValue, (value) => {
    updateFilter(value);
});

watch(
    () => filters[props.name],
    (value) => {
        if (value !== localValue.value) {
            localValue.value = value || '';
        }
    }
);

watchEffect(() => {
    const value = filters[props.name];

    if (!value) {
        registerFilter?.(props.name, null);
        return;
    }

    registerFilter?.(props.name, {
        label: 'contêm',
        format: () => value,
        clear: () => {
            filters[props.name] = null;
        },
    });
});

onUnmounted(() => {
    updateFilter.cancel();
});
</script>

<template>
    <AppInput
        v-model="localValue"
        :label="label"
        :required="false"
        :placeholder="placeholder"
        :icon="icon"
        :width="width"
        class="min-w-[280px] md:max-w-[500px] md:flex-[2]"
    />
</template>
