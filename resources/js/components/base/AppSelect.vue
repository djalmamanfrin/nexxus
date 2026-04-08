<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { SelectOption } from '@/types/select';
import AppLabel from '@/components/base/AppLabel.vue';

interface Props {
    label?: string;
    modelValue?: string | number | (string | number)[];
    multiple?: boolean;
    url?: string;
    options?: SelectOption[];
    width?: string;
    required?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
    required: true,
});

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

const options = computed(() => props.options ?? internalOptions.value);

const onChange = (event: Event) => {
    const target = event.target as HTMLSelectElement;

    if (props.multiple) {
        const selectedValues = Array.from(target.selectedOptions).map(
            (opt) => opt.value,
        );

        const selectedItems = options.value.filter((opt) =>
            selectedValues.includes(String(opt.value)),
        );

        if (!selectedItems) {
            emit('update:modelValue', null);
            emit('selected', null);
            return;
        }
        emit('update:modelValue', selectedValues);
        emit('selected', selectedItems);
    } else {
        const value = target.value;
        const selectedItem = options.value.find(
            (option) => String(option.value) === String(value),
        );

        if (!selectedItem) {
            emit('update:modelValue', null);
            emit('selected', null);
            return;
        }

        emit('update:modelValue', value);
        emit('selected', selectedItem);
    }
};
</script>

<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <AppLabel v-if="label" :label="label" :required="required" />
        <select
            :value="modelValue"
            @change="onChange($event)"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-600 focus:outline-none dark:bg-gray-950"
        >
            <option value="">Selecione</option>

            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
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
