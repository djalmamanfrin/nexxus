<script setup lang="ts">
import { SelectOption } from '@/types/select';

interface Props {
    label?: string;
    modelValue?: string | number;
    options: SelectOption[];
    width?: string;
}

withDefaults(defineProps<Props>(), {
    width: 'w-40',
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <label v-if="label" class="text-xs font-medium text-gray-600">
            {{ label }}
        </label>

        <select
            :value="modelValue"
            @change="emit('update:modelValue', $event.target.value)"
            :class="[
                'w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm',
                'focus:outline-none focus:ring-2 focus:ring-indigo-600',
                width,
            ]"
        >
            <option value="">Todos</option>

            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>
