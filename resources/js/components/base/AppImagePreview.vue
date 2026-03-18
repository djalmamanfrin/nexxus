<script setup lang="ts">
import { ref } from 'vue';

interface FileAttachment {
    id: number;
    original_name: string;
    url: string;
    mime_type: string;
}

const props = defineProps<{
    file: FileAttachment;
}>();

const showModal = ref(false);

const openPreview = () => {
    if (props.file.mime_type.startsWith('image/')) {
        showModal.value = true;
    } else {
        window.open(props.file.url, '_blank');
    }
};

const closePreview = () => {
    showModal.value = false;
};
</script>

<template>
    <div class="inline-block">
        <!-- Link para visualizar -->
        <a
            href="#"
            @click.prevent="openPreview"
            class="text-blue-600 underline"
        >
            {{ file.original_name }}
        </a>

        <!-- Modal de preview -->
        <div
            v-if="showModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            @click.self="closePreview"
        >
            <div
                class="flex max-h-[90%] max-w-[90%] flex-col items-center rounded bg-white p-4 shadow-lg"
            >
                <button
                    @click="closePreview"
                    class="mb-2 self-end text-gray-500 hover:text-gray-800"
                >
                    Fechar ✕
                </button>
                <img
                    :src="props.file.url"
                    :alt="props.file.original_name"
                    class="max-h-[80vh] max-w-full rounded object-contain"
                />
            </div>
        </div>
    </div>
</template>
