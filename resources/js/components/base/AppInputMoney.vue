<template>
    <AppInput
        :label="label"
        :model-value="internalValue"
        :error="error"
        :placeholder="placeholder"
        inputmode="numeric"
        @update:modelValue="handleInput"
        :class="width"
    />
</template>
<script setup>
import { ref, watch } from 'vue';
import AppInput from '@/components/base/AppInput.vue';

const props = defineProps({
    modelValue: {
        type: Number,
        default: null,
    },
    label: String,
    error: String,
    placeholder: {
        type: String,
        default: 'R$ 0,00',
    },
    width: {
        default: 'w-full',
    },
});

const emit = defineEmits(['update:modelValue']);

const internalValue = ref('');

function formatCurrency(value) {
    const amount = Number(value) / 100;

    return amount.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
}

function onlyNumbers(value) {
    return (value || '').replace(/\D/g, '');
}

watch(
    () => props.modelValue,
    (value) => {
        if (!value) {
            internalValue.value = '';
            return;
        }

        const cents = Math.round(Number(value) * 100);
        internalValue.value = formatCurrency(cents);
    },
    { immediate: true },
);

function handleInput(value) {
    const numbers = onlyNumbers(value);

    if (!numbers) {
        internalValue.value = '';
        emit('update:modelValue', null);
        return;
    }

    internalValue.value = formatCurrency(numbers);

    const decimal = Number(numbers) / 100;

    emit('update:modelValue', decimal);
}
</script>
