<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, Head, useForm, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { Save, ArrowLeft } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import { SelectOption } from '@/types/select';
import Create from '@/pages/payments/status/Create.vue';

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
    statuses: SelectOption[];
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Pagamentos', href: '/payments' },
    { title: 'Editar Pagamento', href: '' },
];

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

const handleStatusCreated = (newStatus) => {
    console.log('Novo status criado:', newStatus);
    router.reload({ only: ['statuses'] });
    form.payment_status_id = newStatus.value;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Editar Pagamento" />

        <div class="content-box flex h-full flex-col overflow-hidden">
            <!-- HEADER -->
            <div class="content-box-header flex items-center justify-between">
                <h3 class="content-box-title">Editar Pagamento</h3>

                <div class="flex gap-2">
                    <Link href="/payments" class="btn-warning align-icon-btn">
                        <ArrowLeft class="h-4 w-4" />
                        <span>Voltar</span>
                    </Link>

                    <button
                        type="button"
                        @click="submit"
                        class="btn-success align-icon-btn"
                        :disabled="form.processing"
                    >
                        <Save class="h-4 w-4" />
                        <span>
                            {{ form.processing ? 'Salvando...' : 'Salvar' }}
                        </span>
                    </button>
                </div>
            </div>

            <FlashMessage />

            <!-- CONTEÚDO PRINCIPAL -->
            <div class="flex-1 overflow-hidden">
                <div class="grid h-full grid-cols-1 gap-6 lg:grid-cols-10">
                    <!-- imagem -->
                    <div
                        class="order-1 flex max-h-[80vh] flex-col gap-3 lg:order-2 lg:col-span-3"
                    >
                        <input
                            type="file"
                            accept="image/*"
                            @change="handleFileChange"
                            class="form-input"
                        />

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
                        class="order-2 h-full overflow-auto pr-2 lg:order-1 lg:col-span-7 px-8"
                    >
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="form-label">Conta</label>
                                    <input
                                        v-model="form.bank_account_id"
                                        class="form-input"
                                    />
                                </div>

                                <div>
                                    <label class="form-label">Despesa</label>
                                    <input
                                        v-model="form.expense_id"
                                        class="form-input"
                                    />
                                </div>

                                <div>
                                    <AppSelect
                                        v-model="form.payment_status_id"
                                        @created="handleStatusCreated"
                                        label="Status"
                                        name="status"
                                        width="w-56"
                                        showCreate
                                        :options="statuses"
                                        :createComponent="Create"
                                    />
                                </div>

                                <div>
                                    <label class="form-label">Tipo</label>
                                    <input
                                        v-model="form.payment_type_id"
                                        class="form-input"
                                    />
                                </div>

                                <div>
                                    <label class="form-label">Valor</label>
                                    <input
                                        v-model="form.amount"
                                        class="form-input"
                                    />
                                </div>

                                <div>
                                    <label class="form-label">Pago em</label>
                                    <input
                                        v-model="form.paid_at"
                                        type="datetime-local"
                                        class="form-input"
                                    />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
