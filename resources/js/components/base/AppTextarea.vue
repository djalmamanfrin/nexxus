<script setup lang="ts">
import { ref } from 'vue';
import AppLabel from '@/components/base/AppLabel.vue';

const props = defineProps({
    label: String,
    modelValue: String,
    placeholder: String,
    rows: {
        type: Number,
        default: 3,
    },
    autoResize: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const textareaRef = ref(null);

const handleInput = (e) => {
    emit('update:modelValue', e.target.value);

    if (props.autoResize) {
        resize();
    }
};

const resize = () => {
    const el = textareaRef.value;
    if (!el) return;

    el.style.height = 'auto';
    el.style.height = el.scrollHeight + 'px';
};
</script>

<template>
    <div class="flex w-full flex-col gap-1">
        <AppLabel v-if="label" :label="label" />

        <textarea
            ref="textareaRef"
            v-bind="$attrs"
            :rows="rows"
            :value="modelValue"
            @input="handleInput"
            :placeholder="placeholder"
            class="w-full resize-none rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 dark:bg-gray-950"
        />
    </div>
</template>
