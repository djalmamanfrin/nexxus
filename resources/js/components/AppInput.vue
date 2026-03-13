<script setup>
import { computed } from 'vue'
import {CircleXIcon} from 'lucide-vue-next';

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

const emit = defineEmits([
    'update:modelValue',
    'search'
])

function handleInput(event) {
    const value = event.target.value

    emit('update:modelValue', value)

    if (value.length >= 3) {
        emit('search', value)
    }
}

function clearInput() {
    emit('update:modelValue', '')
}

const hasValue = computed(() => props.modelValue.length > 0)
</script>

<template>
    <div class="relative w-full">
        <component
            v-if="icon"
            :is="icon"
            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
        />

        <!-- Input -->
        <input
            :type="type"
            :value="modelValue"
            @input="handleInput"
            :placeholder="placeholder"
            :class="[
                'w-full form-input pr-9',
                icon ? 'pl-10' : 'pl-3'
            ]"
        />
        <CircleXIcon
            v-if="hasValue"
            type="button"
            @click="clearInput"
            class="absolute cursor-pointer right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-500"
        />
    </div>
</template>
