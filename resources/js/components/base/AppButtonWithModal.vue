<script setup lang="ts">
import { ref } from 'vue';
import { Plus } from 'lucide-vue-next';
import AppFormModal from '@/components/base/AppFormModal.vue';
import AppButton from '@/components/AppButton.vue';
import { SelectOption } from '@/types/select';

interface Props {
    label?: string;
    width?: string;
    createComponent: any;
    title?: string;
    description?: string;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
});

const emit = defineEmits(['created']);

const showModal = ref(false);

const handleCreated = (item: SelectOption) => {
    emit('created', item);
    showModal.value = false;
};
</script>

<template>
    <div class="flex w-full flex-col" :class="width">
        <div class="flex items-center justify-between">
            <AppButton
                @click="showModal = true"
                :label="label"
                :icon="Plus"
                variant="success"
            />
        </div>

        <AppFormModal
            :open="showModal"
            :title="title"
            :description="description"
            @update:open="showModal = $event"
        >
            <component
                :is="createComponent"
                @created="handleCreated"
                @cancel="showModal = false"
            />
        </AppFormModal>
    </div>
</template>
