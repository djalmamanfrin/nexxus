<script setup>
import { inject, onMounted, ref } from 'vue';
import AppDate from '@/components/base/AppDate.vue';
import { formatDateShort } from '@/lib/date.ts';

const filters = inject('filters');
const registerFilter = inject('registerFilter');

const inputRef = ref(null);

const props = defineProps({
    label: String,
    name: String,
    width: String,
});

function openPicker() {
    inputRef.value?.showPicker?.();
}

onMounted(() => {
    registerFilter?.(props.name, {
        label: props.label.toLowerCase(),
        type: 'date',
        format: (value) => formatDateShort(value),
    });
});
</script>

<template>
    <AppDate v-model="filters[name]" :label="label" @click="openPicker" />
</template>
