<script setup lang="ts">
import { ref } from 'vue';
import { Plus } from 'lucide-vue-next';
import { SelectOption } from '@/types/select';
import AppButton from '@/components/AppButton.vue';
import AppFormModal from '@/components/base/AppFormModal.vue';

interface Props {
    label?: string;
    modelValue?: string | number;
    options: SelectOption[];
    width?: string;
    showCreate?: boolean;
    createComponent?: any;
}

withDefaults(defineProps<Props>(), {
    width: 'w-full',
    showCreate: false,
});

const emit = defineEmits(['update:modelValue', 'created']);

const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleCreated = (newItem: any) => {
    emit('created', newItem);
    closeModal();
};

const handleNewStatus = (item) => {
    statusOptions.push({
        value: item.id,
        label: item.name,
    });

    form.payment_status_id = item.id;
};
</script>

<template>
    <div class="flex w-full flex-col gap-1" :class="width">
        <div class="flex items-center justify-between">
            <label v-if="label" class="text-xs font-medium text-gray-600">
                {{ label }}
            </label>

            <AppButton
                v-if="showCreate"
                @click="openModal"
                @created="handleNewStatus"
                label="Criar"
                :icon="Plus"
                variant="link"
                class="flex items-center text-xs font-medium"
            />
        </div>

        <select
            :value="modelValue"
            @change="emit('update:modelValue', $event.target.value)"
            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600"
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
