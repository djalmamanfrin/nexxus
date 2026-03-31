<template>
    <div @click="handleClick" :class="width">
        <AppInput
            ref="inputRef"
            type="date"
            :label="label"
            :model-value="modelValue"
            :error="error"
            :placeholder="placeholder"
            @update:modelValue="handleInput"
        />
    </div>
</template>
<script setup>
import { ref } from 'vue';
import AppInput from '@/components/base/AppInput.vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: null,
    },
    label: String,
    error: String,
    placeholder: {
        type: String,
        default: 'Selecione uma data',
    },
    width: {
        default: 'w-full',
    },
});

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);

function handleClick() {
    inputRef.value?.inputRef?.showPicker?.();
}

function handleInput(value) {
    emit('update:modelValue', value);

    setTimeout(() => {
        inputRef.value?.blur();
    }, 0);
}
</script>
