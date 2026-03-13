<script setup>
const props = defineProps({
    modelValue: [String, Number],
    options: Array,
    width: {
        default: 'w-40'
    }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <select
        :value="modelValue"
        @change="emit('update:modelValue', $event.target.value)"
        :class="[
            'border border-gray-300 rounded-md px-3 py-2 bg-white text-sm',
            'focus:outline-none focus:ring-2 focus:ring-indigo-600',
            width
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
</template>
