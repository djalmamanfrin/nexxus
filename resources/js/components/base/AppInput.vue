<script setup>
const props = defineProps({
    label: String,
    modelValue: String,
    placeholder: String,
    icon: Function,
    width: {
        default: 'w-40',
    },
});

const emit = defineEmits(['update:modelValue']);
</script>

<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <label v-if="label" class="text-xs font-medium text-gray-600">
            {{ label }}
        </label>

        <div class="relative">
            <component
                v-if="icon"
                :is="icon"
                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
            />
            <input
                v-bind="$attrs"
                :value="modelValue"
                @input="emit('update:modelValue', $event.target.value)"
                :placeholder="placeholder"
                :class="icon ? 'pl-10' : ''"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
            />
        </div>
    </div>
</template>
