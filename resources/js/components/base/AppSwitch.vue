<script setup lang="ts">
import AppLabel from '@/components/base/AppLabel.vue';

const props = defineProps({
    label: String,
    modelValue: {
        type: Boolean,
        default: false,
    },
    disabled: Boolean,
});

const emit = defineEmits(['update:modelValue']);

const toggle = () => {
    if (props.disabled) return;
    emit('update:modelValue', !props.modelValue);
};
</script>

<template>
    <div
        @click="toggle"
        :class="[
            'flex w-full items-center justify-between rounded-md border px-3 py-2 text-sm transition',
            'border-gray-300 bg-white dark:bg-gray-950',
            !disabled && 'cursor-pointer hover:border-indigo-400',
            disabled && 'opacity-50 cursor-not-allowed'
        ]"
    >
        <!-- Label -->
        <span class="text-sm text-gray-700 dark:text-gray-200">
            {{ label }}
        </span>

        <!-- Toggle -->
        <div
            :class="[
                'relative inline-flex h-5 w-10 items-center rounded-full transition',
                modelValue ? 'bg-indigo-600' : 'bg-gray-300'
            ]"
        >
            <span
                :class="[
                    'inline-block h-4 w-4 transform rounded-full bg-white transition',
                    modelValue ? 'translate-x-5' : 'translate-x-1'
                ]"
            />
        </div>
    </div>
</template>
