<script setup lang="ts">
import api from '@/lib/axios';
import { router, useForm } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';
import { CirclePlus, Upload, RefreshCw, Loader2 } from 'lucide-vue-next';
import AppFormModal from '@/components/base/AppFormModal.vue';
import AppButton from '@/components/AppButton.vue';

const emit = defineEmits(['created']);

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    btnIcon: {
        type: Function,
        default: CirclePlus,
    },
    btnVariant: {
        type: String,
        default: 'success',
    },
});

const open = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

const form = useForm<{
    attachment: File | null;
    attachmentPreview: string | null;
}>({
    attachment: null,
    attachmentPreview: null,
});

function openFileSelector() {
    fileInput.value?.click();
}

const submit = async () => {
    if (!form.isDirty) {
        form.setError('attachment', 'Arquivo não selecionado');
        return;
    }
    try {
        const response = await api.post(props.url, form.data(), {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        if (response.status === 201) {
            open.value = false;
            emit('created', {
                label: response.data.label,
                value: response.data.value,
                field: response.data.field,
            });
            router.reload();
            if (form.attachmentPreview) {
                URL.revokeObjectURL(form.attachmentPreview);
            }
            form.reset();
        } else {
            console.warn('Status code inesperado:', response.status);
        }
    } catch (error: any) {
        console.error('Erro no upload:', error);
    }
};

function handleFile(e: Event) {
    const target = e.target as HTMLInputElement;

    if (!target.files?.length) return;

    const file = target.files[0];

    if (!file.type.startsWith('image/')) {
        alert('Selecione apenas imagens');
        return;
    }

    if (form.attachmentPreview) {
        URL.revokeObjectURL(form.attachmentPreview);
    }

    form.attachment = file;
    form.attachmentPreview = URL.createObjectURL(file);

    open.value = true;
}

onUnmounted(() => {
    if (form.attachmentPreview) {
        URL.revokeObjectURL(form.attachmentPreview);
    }
});
</script>

<template>
    <AppButton
        v-bind="$attrs"
        @click="openFileSelector"
        :label="props.label"
        :icon="props.btnIcon"
        :variant="props.btnVariant"
    />

    <input
        ref="fileInput"
        type="file"
        class="hidden"
        accept="image/*"
        @change="handleFile"
    />

    <AppFormModal
        :open="open"
        :title="props.label"
        description="Confirme se selecionou a imagem correta antes de salvar"
        @update:open="(value) => (open = value)"
    >
        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="flex justify-center">
                <div
                    class="relative max-h-[500px] overflow-hidden overflow-y-auto rounded-lg"
                >
                    <img
                        :src="form.attachmentPreview"
                        class="w-full cursor-zoom-in object-cover"
                        @click="openViewer = true"
                        alt="Comprovante anexado"
                    />

                    <!-- fade no final -->
                    <div
                        class="pointer-events-none absolute bottom-0 h-20 w-full bg-gradient-to-t from-white to-transparent"
                    ></div>
                </div>
            </div>

            <AppButton
                @click="openFileSelector"
                label="Trocar Imagem"
                :icon="RefreshCw"
                variant="secondary"
            />

            <AppButton
                type="submit"
                :label="form.processing ? 'Salvando...' : 'Salvar Comprovante'"
                :disabled="form.processing || !form.attachment"
                :icon="form.processing ? Loader2 : Upload"
                variant="success"
            />
        </form>
    </AppFormModal>
</template>
