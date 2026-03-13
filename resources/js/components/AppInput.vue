<script setup>

const props = defineProps({
    modelValue: String,
    placeholder: String,
    width: {
        default: 'w-64'
    }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <input
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :class="[
            'border border-gray-300 rounded-md px-3 py-2 bg-white text-sm',
            'focus:outline-none focus:ring-2 focus:ring-indigo-600',
            width
        ]"
    />
</template>
