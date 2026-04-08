<template>
    <div class="flex flex-col gap-1">
        <AppLabel v-if="label" :label="label" />

        <div class="flex items-end gap-2">
            <AppInputDate
                :model-value="startValue"
                :label="startLabel"
                :error="startError"
                placeholder="Data inicial"
                width="w-56"
                @update:modelValue="updateStart"
            />

            <span class="pb-2 text-gray-400">até</span>

            <AppInputDate
                :model-value="endValue"
                :label="endLabel"
                :error="endError"
                placeholder="Data final"
                width="w-56"
                @update:modelValue="updateEnd"
            />
        </div>
    </div>
</template>

<script setup>
import { computed, watchEffect } from 'vue';
import { inject } from 'vue';
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppLabel from '@/components/base/AppLabel.vue';

const props = defineProps({
    startName: {
        type: String,
        required: true,
    },
    endName: {
        type: String,
        required: true,
    },
    label: String,
    startLabel: {
        type: String,
        default: '',
    },
    endLabel: {
        type: String,
        default: '',
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['change']);

const filters = inject('filters');
const registerFilter = inject('registerFilter');

function formatDate(date) {
    return new Date(date).toLocaleDateString('pt-BR');
}

watchEffect(() => {
    const start = filters[props.startName];
    const end = filters[props.endName];

    const metaKey = `${props.startName}_${props.endName}`;

    if (!start && !end) {
        registerFilter?.(metaKey, null);
        return;
    }

    registerFilter?.(metaKey, {
        label: props.label || 'no período',
        format: () => {
            if (start && end) return `${formatDate(start)} até ${formatDate(end)}`;
            if (start) return `a partir de ${formatDate(start)}`;
            if (end) return `até ${formatDate(end)}`;
            return '';
        },
        clear: () => {
            filters[props.startName] = null;
            filters[props.endName] = null;
        },
    });
});

const startValue = computed(() => filters[props.startName]);
const endValue = computed(() => filters[props.endName]);

const startError = computed(() => props.errors?.[props.startName]);
const endError = computed(() => props.errors?.[props.endName]);

function updateStart(value) {
    filters[props.startName] = value;
}

function updateEnd(value) {
    filters[props.endName] = value;
}
</script>
