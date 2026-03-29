<script setup lang="ts">
import { ref } from 'vue';
import { Plus } from 'lucide-vue-next';
import AppFormModal from '@/components/base/AppFormModal.vue';
import AppButton from '@/components/AppButton.vue';

interface Props {
    label?: string;
    title?: string;
    description?: string;
    width?: string;
}

const props = withDefaults(defineProps<Props>(), {
    width: 'w-full',
});

const showModal = ref(false);

const handleClose = () => {
    showModal.value = false;
};
</script>

<template>
    <div :class="['flex flex-col', width]">
        <AppButton
            @click="showModal = true"
            :label="label"
            :icon="Plus"
            variant="success"
        />

        <AppFormModal
            :open="showModal"
            :title="title"
            :description="description"
            @update:open="showModal = $event"
        >
            <slot
                name="default"
                :close="handleClose"
            />
        </AppFormModal>
    </div>
</template>
