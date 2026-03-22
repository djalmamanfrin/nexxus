<template>
    <div class="flex w-full flex-col" :class="width">
        <!-- Label + botão -->
        <div class="flex items-center justify-between">
            <label
                v-if="label"
                class="text-sm font-medium text-gray-600 dark:text-gray-300"
            >
                {{ label }}
            </label>

            <AppButton
                v-if="showCreate"
                @click="openModal"
                label="Criar"
                :icon="Plus"
                variant="link"
                class="flex items-center text-xs font-medium"
            />
        </div>

        <!-- Select -->
        <select
            :value="modelValue"
            @change="emit('update:modelValue', Number($event.target.value))"
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

        <!-- Loading -->
        <span v-if="loading" class="mt-1 text-xs text-gray-500">
            Carregando...
        </span>

        <!-- Error -->
        <span v-if="error" class="mt-1 text-xs text-red-500">
            {{ error }}
        </span>

        <!-- Modal -->
        <AppFormModal
            :open="showModal"
            title="Novo status de pagamento"
            description="Como gostaria de nomear este novo status?"
            @update:open="(value) => (showModal = value)"
        >
            <component
                :is="createComponent"
                @created="handleCreated"
                @cancel="closeModal"
            />
        </AppFormModal>
    </div>
</template>
<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { Plus } from 'lucide-vue-next';
import { SelectOption } from '@/types/select';
import AppButton from '@/components/AppButton.vue';
import AppFormModal from '@/components/base/AppFormModal.vue';

interface Props {
    label?: string;
    modelValue?: string | number;
    url: string;
    width?: string;
    showCreate?: boolean;
    createComponent?: any;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
    showCreate: false,
});

const emit = defineEmits(['update:modelValue', 'created']);

const options = ref<SelectOption[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const fetchOptions = async () => {
    if (!props.url) return;

    loading.value = true;
    error.value = null;

    try {
        const { data } = await axios.get<SelectOption[]>(props.url);
        options.value = data;
    } catch (e) {
        console.error(e);
        error.value = 'Erro ao carregar opções';
    } finally {
        loading.value = false;
    }
};

onMounted(fetchOptions);
watch(() => props.url, fetchOptions);

const handleCreated = (item: SelectOption) => {
    closeModal();
    options.value.push(item);
    emit('update:modelValue', item.value);
    emit('created', item);
};
</script>
