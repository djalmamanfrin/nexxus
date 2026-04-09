<script setup lang="ts">
import { SelectOption } from '@/types/select';
import axios from 'axios';
import { computed, onMounted, ref, watch } from 'vue';
import AppLabel from '@/components/base/AppLabel.vue';

const props = defineProps<{
    label?: string;
    modelValue?: string | number | (string | number)[];
    multiple?: boolean;
    url?: string;
    options?: SelectOption[];
    width?: string;
    required?: boolean;
}>();

const emit = defineEmits(['update:modelValue', 'selected']);

const internalOptions = ref<SelectOption[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

const fetchOptions = async () => {
    if (!props.url) return;

    loading.value = true;
    error.value = null;

    try {
        const { data } = await axios.get<SelectOption[]>(props.url);
        internalOptions.value = data;
    } catch (e) {
        console.error(e);
        error.value = 'Erro ao carregar opções';
    } finally {
        loading.value = false;
    }
};

onMounted(fetchOptions);
watch(() => props.url, fetchOptions);

const options = computed<SelectOption[]>(() => {
    if (Array.isArray(props.options)) {
        return props.options;
    }

    return internalOptions.value;
});

const isSelected = (value: string | number) => {
    if (props.multiple) {
        return Array.isArray(props.modelValue)
            ? props.modelValue.map(String).includes(String(value))
            : false;
    }

    return String(props.modelValue) === String(value);
};

const onChange = (event: Event) => {
    const target = event.target as HTMLSelectElement;

    if (props.multiple) {
        const selectedValues = Array.from(target.selectedOptions).map(
            (opt) => opt.value,
        );

        const selectedItems = options.value.filter((opt) =>
            selectedValues.includes(String(opt.value)),
        );

        emit('update:modelValue', selectedValues);
        emit('selected', selectedItems);

        return;
    }

    const value = target.value;

    const selectedItem = options.value.find(
        (opt) => String(opt.value) === String(value),
    );

    emit('update:modelValue', value || null);
    emit('selected', selectedItem || null);
};
</script>
<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <AppLabel v-if="label" :label="label" :required="required" />

        <select
            :multiple="multiple"
            @change="onChange"
            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:outline-none dark:bg-gray-950"
        >
            <option v-if="!multiple" value="">Selecione</option>

            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
                :selected="isSelected(option.value)"
            >
                {{ option.label }}
            </option>
        </select>

        <span v-if="loading" class="mt-1 text-xs text-gray-500">
            Carregando...
        </span>

        <span v-if="error" class="mt-1 text-xs text-red-500">
            {{ error }}
        </span>
    </div>
</template>
