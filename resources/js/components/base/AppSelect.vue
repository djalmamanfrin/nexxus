<script setup>
const props = defineProps({
    label: String,
    modelValue: [String, Number],
    options: Array,
    width: {
        default: 'w-40'
    }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div class="flex flex-col gap-1" :class="width">
        <label
            v-if="label"
            class="text-xs font-medium text-gray-600"
        >
            {{ label }}
        </label>

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
    </div>
</template>
