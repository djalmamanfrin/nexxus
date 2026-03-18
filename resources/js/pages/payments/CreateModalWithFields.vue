<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { onUnmounted } from 'vue';
import { CirclePlus } from 'lucide-vue-next';
import AppFormModal from '@/components/base/AppFormModal.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import { SelectOption } from '@/types/select';

const props = defineProps<{
    open: boolean;
    options: SelectOption[];
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

const form = useForm<{
    amount: string | number;
    payment_type: string;
    attachment: File | null;
    attachmentPreview: string | null;
}>({
    amount: '',
    payment_type: '',
    attachment: null,
    attachmentPreview: null,
});

function submit() {
    form.post('/payments', {
        forceFormData: true,
        onSuccess: () => {
            emit('update:open', false);

            // limpa preview corretamente
            if (form.attachmentPreview) {
                URL.revokeObjectURL(form.attachmentPreview);
            }

            form.reset();
        },
    });
}

function handleFile(e: Event) {
    const target = e.target as HTMLInputElement;

    if (!target.files?.length) return;

    const file = target.files[0];

    // validação simples
    if (!file.type.startsWith('image/')) {
        alert('Selecione apenas imagens');
        return;
    }

    // limpa preview anterior (evita vazamento de memória)
    if (form.attachmentPreview) {
        URL.revokeObjectURL(form.attachmentPreview);
    }

    form.attachment = file;
    form.attachmentPreview = URL.createObjectURL(file);
}

// limpeza ao destruir componente
onUnmounted(() => {
    if (form.attachmentPreview) {
        URL.revokeObjectURL(form.attachmentPreview);
    }
});
</script>

<template>
    <AppFormModal
        :open="open"
        title="Novo pagamento"
        @update:open="(value) => emit('update:open', value)"
    >
        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <!-- GRID PRINCIPAL -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- 🖼️ ESQUERDA: Upload + Preview -->
                <div class="flex flex-col gap-4">
                    <div
                        class="flex h-48 items-center justify-center rounded-lg border-2 border-dashed p-4"
                    >
                        <template v-if="form.attachmentPreview">
                            <img
                                :src="form.attachmentPreview"
                                class="max-h-full object-contain"
                                alt=""
                            />
                        </template>

                        <template v-else>
                            <span class="text-sm text-gray-500">
                                Pré-visualização
                            </span>
                        </template>
                    </div>

                    <input type="file" @change="handleFile" />
                </div>

                <!-- 📋 DIREITA: Campos -->
                <div class="flex flex-col gap-4">
                    <AppInput
                        v-model="form.amount"
                        type="number"
                        label="Valor"
                        placeholder="Valor"
                    />

                    <AppSelect
                        v-model="form.payment_type"
                        label="Tipo de pagamento"
                        :options="props.options"
                    />

                    <span class="required-field text-sm">
                        * Campo obrigatório
                    </span>
                </div>
            </div>

            <!-- 🔽 BOTÃO FULL WIDTH -->
            <button
                type="submit"
                class="btn-success flex w-full items-center justify-center gap-2"
                :disabled="form.processing"
            >
                <CirclePlus class="h-4 w-4" />
                <span>
                    {{ form.processing ? 'Salvando...' : 'Salvar' }}
                </span>
            </button>

            <FlashMessage />
        </form>
    </AppFormModal>
</template>
