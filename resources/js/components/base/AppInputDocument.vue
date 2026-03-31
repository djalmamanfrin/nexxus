<script setup>
import { ref, watch } from 'vue';
import AppInput from '@/components/base/AppInput.vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: String,
    error: String,
    placeholder: {
        type: String,
        default: 'CPF ou CNPJ',
    },
});

const emit = defineEmits(['update:modelValue', 'max-length']);

const internalValue = ref('');

function onlyNumbers(value) {
    return (value || '').replace(/\D/g, '');
}

function maskCPF(value) {
    const v = value.slice(0, 11);

    return v
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
}

function maskCNPJ(value) {
    const v = value.slice(0, 14);

    return v
        .replace(/^(\d{2})(\d)/, '$1.$2')
        .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
        .replace(/\.(\d{3})(\d)/, '.$1/$2')
        .replace(/(\d{4})(\d)/, '$1-$2');
}

function maskDocument(value) {
    const numbers = onlyNumbers(value);

    return numbers.length <= 11 ? maskCPF(numbers) : maskCNPJ(numbers);
}

watch(
    () => props.modelValue,
    (value) => {
        internalValue.value = maskDocument(value);
    },
    { immediate: true },
);

function handleInput(value) {
    let numbers = onlyNumbers(value);
    internalValue.value = maskDocument(numbers);

    emit('update:modelValue', numbers);
}
</script>

<template>
    <AppInput
        :label="label"
        :model-value="internalValue"
        :error="error"
        :placeholder="placeholder"
        @update:modelValue="handleInput"
        maxlength="18"
    />
</template>
