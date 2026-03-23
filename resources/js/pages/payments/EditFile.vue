<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

interface Payment {
    id: number;
    expense_id: string;
    bank_account_id: string;
    payment_status_id: string;
    payment_type_id: string;
    amount: string;
    paid_at: string;
    attachments?: any[];
}

const props = defineProps<{
    payment: Payment;
}>();

const form = useForm({
    expense_id: props.payment.expense_id ?? '',
    bank_account_id: props.payment.bank_account_id ?? '',
    payment_status_id: props.payment.payment_status_id ?? '',
    payment_type_id: props.payment.payment_type_id ?? '',
    amount: props.payment.amount ?? '',
    paid_at: props.payment.paid_at ?? '',
});

const submit = () => {
    form.put(`/payments/${props.payment.id}`);
};

const previewUrl = ref<string | null>(null);

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.file = file;
    previewUrl.value = URL.createObjectURL(file);
};

const handleCreated = (item) => {
    router.reload({ only: ['statuses'] });
    form[item.field] = item.value;
};
</script>

<template>
    <div
        class="order-1 flex max-h-[80vh] flex-col gap-3 lg:order-2 lg:col-span-3"
    >
        <div
            class="flex-1 overflow-auto rounded-lg bg-gray-50 p-3"
        >
            <img
                v-if="previewUrl || payment.attachments?.length"
                :src="previewUrl || payment.attachments[0].url"
                class="w-full object-contain"
                alt=""
            />

            <span v-else class="text-gray-400">
                Nenhuma imagem selecionada
            </span>
        </div>
    </div>

    <!-- form -->
    <div
        class="order-2 col-span-1 h-full overflow-auto md:col-span-5 lg:order-2 lg:col-span-3"
    >
        <div class="mb-8">
            <input
                type="file"
                accept="image/*"
                @change="handleFileChange"
                class="form-input"
            />
        </div>
    </div>
</template>
