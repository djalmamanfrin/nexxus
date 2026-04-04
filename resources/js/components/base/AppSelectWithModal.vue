<script setup lang="ts">
import { ref } from 'vue';
import { Plus } from 'lucide-vue-next';
import AppSelect from './AppSelect.vue';
import AppFormModal from '@/components/base/AppFormModal.vue';
import AppButton from '@/components/AppButton.vue';
import { SelectOption } from '@/types/select';

interface Props {
    label?: string;
    modelValue?: string | number;
    url: string;
    width?: string;
    title?: string;
    description?: string;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
});

const emit = defineEmits(['update:modelValue', 'created', 'selected']);

const showModal = ref(false);
const options = ref<SelectOption[]>([]);

const handleCreated = (item: SelectOption) => {
    options.value.push(item);

    emit('update:modelValue', item.value);
    emit('created', item);

    showModal.value = false;
};
</script>

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
                @click="showModal = true"
                label="Criar"
                :icon="Plus"
                variant="link"
                class="text-xs"
            />
        </div>

        <AppSelect
            :modelValue="modelValue"
            :url="url"
            :options="options.length ? options : undefined"
            @update:modelValue="emit('update:modelValue', $event)"
            @selected="emit('selected', $event)"
        />

        <AppFormModal
            :open="showModal"
            :title="title"
            :description="description"
            @update:open="showModal = $event"
        >
            <slot
                name="create"
                @created="handleCreated"
                @cancel="showModal = false"
            />
        </AppFormModal>
    </div>
</template>
