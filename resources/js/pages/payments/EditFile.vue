<script setup lang="ts">
import { ref, onUnmounted } from 'vue';
import AppButton from '@/components/AppButton.vue';

interface Payment {
    id: number;
    attachments?: any[];
}

const props = defineProps<{
    payment: Payment;
    form: {
        attachment: File | null;
    };
}>();

const previewUrl = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

function openFileSelector() {
    fileInput.value?.click();
}

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Selecione apenas imagens');
        return;
    }

    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }

    props.form.attachment = file;
    previewUrl.value = URL.createObjectURL(file);
};

onUnmounted(() => {
    if (previewUrl.value) {
        URL.revokeObjectURL(previewUrl.value);
    }
});
</script>

<template>
    <div class="mb-4 rounded-lg bg-neutral-100 dark:bg-neutral-800">
        <input
            ref="fileInput"
            type="file"
            accept="image/*"
            @change="handleFileChange"
            class="hidden"
        />

        <AppButton
            label="Clique para trocar a imagem"
            type="button"
            @click="openFileSelector"
            class="flex w-full items-center justify-center gap-2 rounded-lg bg-gray-50 px-4 py-6 text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:bg-neutral-700 dark:text-neutral-100"
        />
    </div>

    <div class="flex flex-1 justify-center">
        <span v-if="props.form.errors.message" class="text-sm text-red-500">
            {{ props.form.errors.message }}
        </span>
    </div>

    <div
        class="flex-1 overflow-auto rounded-lg bg-gray-50 px-16 py-8 dark:bg-neutral-700"
    >
        <img
            v-if="previewUrl || payment.attachments?.length"
            :src="previewUrl || payment.attachments?.[0]?.url"
            class="w-full rounded-lg object-contain"
            alt=""
        />

        <span v-else class="text-gray-400 dark:text-gray-500">
            Nenhuma imagem selecionada
        </span>
    </div>
</template>
