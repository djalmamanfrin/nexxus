<script setup lang="ts">
import api from '@/lib/axios';
import { watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Loader2, Save } from 'lucide-vue-next';
import AppButton from '@/components/AppButton.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppTextarea from '@/components/base/AppTextarea.vue';

const emit = defineEmits(['created', 'cancel']); // mudar de cancel para close

const form = useForm<{
    code: string;
    budget: number;
    description: string;
}>({
    code: '',
    budget: 0,
    description: '',
});

const submit = async () => {
    if (!form.code) {
        form.setError('code', 'O campo nome é obrigatório');
        return;
    }
    if (!form.budget) {
        form.setError('budget', 'O campo orçamento é obrigatório');
        return;
    }
    try {
        const response = await api.post('/cost-centers', form.data());
        if (response.status === 201) {
            emit('created', {
                field: 'payment_status_id',
                value: response.data.id,
                label: response.data.code,
            });

            router.reload({ only: ['statuses'] });
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
    () => form.code,
    (value) => {
        if (!value) return;
        if (value.length === 12) {
            form.setError(
                'code',
                'O código chegou no limite máximo de 12 caracteres',
            );
        }

        const words = value.trim().split(/\s+/);
        if (words.length > 1) {
            form.code = words.slice(0, 1).join(' ');
            form.setError('code', 'O código não deve ter espaços');
        }
    },
);
</script>

<template>
    <div class="space-y-3">
        <div>
            <AppInput
                v-model="form.code"
                label="Código"
                maxlength="12"
                placeholder="Ex: 0010.0001"
            />
            <div class="min-h-[20px] text-sm">
                <span v-show="form.errors.code" class="block text-red-500">
                    {{ form.errors.code }}
                </span>
            </div>
        </div>
        <div>
            <AppInput
                v-model="form.budget"
                label="Orçamento"
                maxlength="12"
                placeholder="R$ 25.000"
                mask="currency"
            />
            <div class="min-h-[20px] text-sm">
                <span v-show="form.errors.budget" class="block text-red-500">
                    {{ form.errors.budget }}
                </span>
            </div>
        </div>
        <div>
            <AppTextarea
                v-model="form.description"
                label="Descrição"
                placeholder="Uma descrição que ajuda a identificar o centro de custo"
            />
            <div class="min-h-[20px] text-sm">
                <span
                    v-show="form.errors.description"
                    class="block text-red-500"
                >
                    {{ form.errors.description }}
                </span>
            </div>
        </div>

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
