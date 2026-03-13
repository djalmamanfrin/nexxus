<script setup>

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: ''
    },
    icon: {
        type: Object,
        default: null
    },
    type: {
        type: String,
        default: 'text'
    }
})

const emit = defineEmits(['update:modelValue'])

</script>

<template>
    <div class="relative w-full">
        <component
            v-if="icon"
            :is="icon"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
        />
        <input
            :type="type"
            :value="modelValue"
            @input="emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :class="[
                'w-full form-input',
                icon ? 'pl-10' : 'pl-3'
            ]"
        />
    </div>
</template>
