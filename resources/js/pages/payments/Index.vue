<script setup lang="ts">
import { type BreadcrumbItem, Payment } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import { SelectOption } from '@/types/select';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import EditFields from '@/pages/payments/EditFields.vue';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFileInput from '@/components/base/AppFileInput.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSelect from '@/components/base/AppSelect.vue';
import AppSelectWithUpload from '@/components/base/AppSelectWithUpload.vue';
import AppSelectWithModal from '@/components/base/AppSelectWithModal.vue';
import CreateWork from '@/pages/payees/CreateWork.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AppButtonWithModal from '@/components/base/AppButtonWithModal.vue';
import AppUploadModal from '@/components/base/AppUploadModal.vue';

const props = defineProps<{
    payments: {
        data: Payment[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    statuses: SelectOption[];
    search_by?: string;
    status?: string | number | null;
}>();

const url = '/payments';

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
        status: props.status || null,
    },
    url,
);

const columns = [
    { key: 'attachments', label: 'Imagem', align: 'left' },
    { key: 'amount', label: 'Valor', type: 'money' },
    { key: 'status.name', label: 'Status' },
    { key: 'paid_at', label: 'Pago em', type: 'datetime' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pagamentos',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <AppUploadModal label="Novo pagamento" :url="url" />
        </template>
        <CrudIndexPage
            :items="props.payments"
            :columns="columns"
            :base-url="url"
            v-model:filters="filters"
            :search="search"
            :clear="clear"
            :initialForm="{
                expense_id: null,
                bank_account_id: null,
                payment_status_id: null,
                payment_type_id: null,
                amount: null,
                paid_at: null,
            }"
            :mapToForm="
                (item) => ({
                    expense_id: item.expense_id ?? null,
                    bank_account_id: item.bank_account_id ?? null,
                    amount: item.amount,
                    paid_at: item.paid_at,
                })
            "
        >
            <template #filters>
                <FilterText
                    label="Buscar pagamneto"
                    name="search_by"
                    placeholder="CPF, CNPJ ou texto"
                />
                <FilterTabs
                    v-if="statuses?.length"
                    label="Status"
                    name="status"
                    :tabs="statuses"
                />

                <!--                    <FilterSelect-->
                <!--                        :options="statuses"-->
                <!--                        :selectedValue="status"-->
                <!--                        label="Status"-->
                <!--                        name="status"-->
                <!--                        width="w-56"-->
                <!--                    />-->
            </template>

            <template #title> Pagamento </template>

            <template #form="{ item, form }">
                <AppFormLayout :item="item">
                    <AppSelect
                        v-model="form.bank_account_id"
                        url="bank-accounts"
                        label="Conta Bancária"
                        name="bank_id"
                    />

                    <AppSelectWithUpload
                        v-model="form.expense_id"
                        @created="({ field, value }) => (form[field] = value)"
                        :url="`/expenses/${item.id}/payment-options`"
                        label="Despesa"
                        name="expense_id"
                        width="w-56"
                    />

                    <!--            <AppSelectWithModal-->
                    <!--                v-model="form.payment_status_id"-->
                    <!--                showCreate-->
                    <!--                @created="handleCreated"-->
                    <!--                :createComponent="CreateStatus"-->
                    <!--                url="payment-statuses"-->
                    <!--                label="Status"-->
                    <!--                name="status"-->
                    <!--                width="w-56"-->
                    <!--                title="Novo status"-->
                    <!--                description="Como deseja nomear?"-->
                    <!--            />-->

                    <!--            <div>-->
                    <!--                <label class="form-label">Tipo</label>-->
                    <!--                <input v-model="form.payment_type_id" class="form-input" />-->
                    <!--            </div>-->

                    <AppInput
                        v-model="form.amount"
                        :error="form.errors.amount"
                        label="Valor"
                        mask="currency"
                    />

                    <AppInput
                        v-model="form.paid_at"
                        :error="form.errors.paid_at"
                        label="Pago em"
                        type="datetime-local"
                    />
                </AppFormLayout>
            </template>

            <template #file="{ item, form }">
                <AppFileInput :attachments="item?.attachments" :form="form" />
            </template>
        </CrudIndexPage>
    </AppLayout>
</template>
