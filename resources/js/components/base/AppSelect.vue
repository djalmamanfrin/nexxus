<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import AppLabel from '@/components/base/AppLabel.vue';
import type { SelectOption } from '@/types/select';
import type { PropType } from 'vue';

const props = defineProps({
    label: String,
    modelValue: {
        type: [String, Number] as PropType<string | number>,
        default: '',
    },
    url: {
        type: String,
        default: '',
    },
    options: {
        type: Array as PropType<SelectOption[]>,
        default: () => [],
    },
    error: {
        type: String,
        default: '',
    },
    width: {
        type: String,
        default: 'w-full',
    },
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | number): void;
    (e: 'selected', item: SelectOption | undefined): void;
}>();

// Estado interno
const internalOptions = ref<SelectOption[]>([]);
const loading = ref(false);
const fetchError = ref<string | null>(null);

const fetchOptions = async () => {
    if (!props.url) return;

    loading.value = true;
    fetchError.value = null;

    try {
        const { data } = await axios.get<SelectOption[]>(props.url);
        internalOptions.value = data;
    } catch (e) {
        console.error(e);
        fetchError.value = 'Erro ao carregar opções';
    } finally {
        loading.value = false;
    }
};

onMounted(fetchOptions);
watch(() => props.url, fetchOptions);

// Combina opções passadas por prop com opções da API
const options = computed(() => {
    return props.options.length ? props.options : internalOptions.value;
});

const onChange = (value: string | number) => {
    const selectedItem = options.value.find(
        (option) => String(option.value) === String(value)
    );
    emit('update:modelValue', value);
    emit('selected', selectedItem);
};
</script>

<template>
    <div :class="width" class="flex flex-col gap-1">
        <AppLabel v-if="label" :label="label" />

        <select
            :value="modelValue"
            @change="onChange($event.target.value)"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 dark:bg-gray-950"
        >
            <option value="">Selecione</option>
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.label }}
            </option>
        </select>

        <span v-if="loading" class="mt-1 text-xs text-gray-500">Carregando...</span>
        <span v-if="fetchError" class="mt-1 text-xs text-red-500">{{ fetchError }}</span>
        <span v-if="props.error" class="mt-1 text-xs text-red-500">{{ props.error }}</span>
    </div>
</template>
