<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { SelectOption } from '@/types/select';
import AppLabel from '@/components/base/AppLabel.vue';

interface Props {
    label?: string;
    modelValue?: string | number;
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

const onChange = (value: string) => {
    const selectedItem = options.value.find(
        (option) => String(option.value) === String(value)
    );

    // TODO: BUG - quando a opcao do opction selecionada é selecione o selectedItem é undefined
    // Confirmar se if abaixo resolveu
    emit('update:modelValue', value);
    if (!selectedItem) {
        emit('selected', null);
        return;
    }
    emit('selected', selectedItem);
};
</script>

<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <AppLabel v-if="label" :label="label" :required="required" />
        <select
            :value="modelValue"
            @change="onChange($event.target.value)"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 dark:bg-gray-950"
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
