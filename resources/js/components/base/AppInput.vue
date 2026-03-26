<script setup>
import { ref, computed, watch } from 'vue';
import { formatDateTimeLocal, parseDateTimeLocal } from '@/lib/date.ts';
import AppLabel from '@/components/base/AppLabel.vue';

const props = defineProps({
    label: String,
    modelValue: [String, Number],
    placeholder: String,
    icon: Function,
    mask: String,
    type: {
        type: String,
        default: 'text',
    },
    width: {
        default: 'w-40',
    },
});

const emit = defineEmits(['update:modelValue']);
const internalValue = ref('');
const formatCurrency = (value) => {
    const numbers = String(value).replace(/\D/g, '');
    const amount = Number(numbers) / 100;

    return amount.toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
};

const parseCurrency = (value) => {
    return value.replace(/\D/g, '');
};

watch(
    () => props.modelValue,
    (value) => {
        if (props.mask === 'currency') {
            if (!value) {
                internalValue.value = '';
                return;
            }

            const numeric = Number(value);
            internalValue.value = String(Math.round(numeric * 100));
        }
    },
    { immediate: true },
);

const displayValue = computed(() => {
    if (props.mask === 'currency') {
        if (!internalValue.value) return '';
        return formatCurrency(internalValue.value);
    }

    if (props.type === 'datetime-local') {
        return formatDateTimeLocal(props.modelValue);
    }

    return props.modelValue;
});

const handleInput = (e) => {
    const raw = e.target.value;

    if (props.mask === 'currency') {
        const parsed = parseCurrency(raw);
        const decimal = parsed ? Number(parsed) / 100 : null;

        internalValue.value = parsed;
        emit('update:modelValue', decimal);
        return;
    }

    if (props.type === 'datetime-local') {
        emit('update:modelValue', parseDateTimeLocal(raw));
        return;
    }

    emit('update:modelValue', raw);
};

const inputRef = ref(null);
const handleClick = () => {
    if (props.type === 'datetime-local' || props.type === 'date') {
        inputRef.value?.showPicker?.();
    }
};
</script>

<template>
    <div @click="handleClick" class="flex w-full flex-col gap-1" :class="width">
        <AppLabel v-if="label" :label="label" />
        <div class="relative">
            <component
                v-if="icon"
                :is="icon"
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
            />

            <input
                ref="inputRef"
                v-bind="$attrs"
                :type="type"
                :value="displayValue"
                @input="handleInput"
                :placeholder="placeholder"
                :class="icon ? 'pl-10' : ''"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 dark:bg-gray-950"
            />
        </div>
    </div>
</template>
