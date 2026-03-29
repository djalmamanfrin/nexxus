<script setup lang="ts">
import api from '@/lib/axios';
import { watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Loader2, Save } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';
import AppInput from '@/components/base/AppInput.vue';

const emit = defineEmits(['created', 'cancel']); // mudar de cancel para close

const form = useForm<{
    name: string;
}>({
    name: '',
});

const submit = async () => {
    if (!form.name) {
        form.setError('name', 'O campo nome é obrigatório');
        return;
    }
    try {
        const response = await api.post('/payees', form.data());
        if (response.status === 201) {
            emit('created', {
                field: 'work_id',
                value: response.data.id,
                label: response.data.name,
            });

            router.reload();
            emit('cancel');
        } else {
            console.warn('Status inesperado:', response.status);
        }
    } catch (error: any) {
        if (error.response?.status === 422) {
            const errors = error.response.data.errors;
            form.clearErrors();
            Object.keys(errors).forEach((field) => {
                form.setError(field, errors[field][0]);
            });
        } else {
            console.error('Erro ao criar status:', error);
        }
    }
};

watch(
    () => form.name,
    (value) => {
        if (!value) return;
        if (value.length === 30) {
            form.setError(
                'name',
                'O status chegou no limite máximo de 30 caracteres',
            );
        }

        const words = value.trim().split(/\s+/);
        if (words.length > 3) {
            form.name = words.slice(0, 3).join(' ');
            form.setError('name', 'O status deve ter no máximo 3 palavras');
        }
    },
);
</script>

<template>
    <div class="space-y-3">
        <AppInput
            v-model="form.name"
            :error="form.errors.name"
            maxlength="30"
            placeholder="Nome da Obra"
        />
        <div class="flex justify-end">
            <AppButton
                @click="submit"
                type="button"
                :label="form.processing ? 'Salvando...' : 'Salvar'"
                :disabled="form.processing"
                :icon="form.processing ? Loader2 : Save"
                variant="success"
            />
        </div>
    </div>
</template>
