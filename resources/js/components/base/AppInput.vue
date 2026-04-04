<script setup>
import { ref } from 'vue';
import AppLabel from '@/components/base/AppLabel.vue';

const props = defineProps({
    label: String,
    modelValue: [String, Number],
    placeholder: String,
    icon: Function,
    uppercase: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    width: {
        default: 'w-40',
    },
    required: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['update:modelValue']);
const inputRef = ref(null);

defineExpose({
    focus: () => inputRef.value?.focus(),
    blur: () => inputRef.value?.blur(),
    showPicker: () => inputRef.value?.showPicker?.(),
});

const handleInput = (e) => {
    let value = e.target.value;
    if (props.uppercase) {
        value = value
            .replace(/\s+/g, '')
            .toUpperCase();
    }
    emit('update:modelValue', value);
};
</script>

<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <AppLabel v-if="label" :label="label" :required="required" />
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
                :value="modelValue"
                @input="handleInput"
                :placeholder="placeholder"
                :class="[
                    'w-full rounded-md border px-3 py-2 text-sm',
                    'focus:outline-none focus:ring-2 focus:ring-indigo-600',
                    'disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400 disabled:opacity-70',
                    'dark:bg-gray-950 dark:disabled:bg-gray-800',
                    icon ? 'pl-10' : '',
                    error
                        ? 'border-red-500 focus:ring-red-500'
                        : 'border-gray-300',
                ]"
            />
        </div>
        <span v-if="error" class="text-xs text-red-500">
            {{ error }}
        </span>
    </div>
</template>
