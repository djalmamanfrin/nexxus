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
import AppInputDate from '@/components/base/AppInputDate.vue';
import AppInputMoney from '@/components/base/AppInputMoney.vue';
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { useCrud } from '@/composables/useCrud';
import { computed } from 'vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import CrudTable from '@/components/crud/CrudTable.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import CrudDrawer from '@/components/crud/CrudDrawer.vue';

const props = defineProps<{
    payments: {
        data: Payment[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    statuses: SelectOption[];
    search_by?: string;
    status?: string | number | null;
}>();

const columns = [
    { key: 'attachments', label: 'Imagem', align: 'left', type: 'attachment' },
    { key: 'amount.formatted', label: 'Valor' },
    { key: 'status.name', label: 'Status', type: 'badge', color: 'status.color' },
    { key: 'paid_at', label: 'Pago em' },
    { key: 'created_at', label: 'Criado em' },
];

const workSchema = {
    entity: 'payments',
    filters: {
        search_by: props.search_by || '',
        status: props.status || null,
    },
    actions: [
        { name: 'edit', title: 'Editar', icon: PencilIcon },
        { name: 'delete', title: 'Excluir', icon: Trash2Icon },
    ],
    form: {
        initial: {
            expense_id: null,
            bank_account_id: null,
            amount: null,
            paid_at: null,
        },

        map: (item: any) => ({
            expense_id: item.expense_id ?? null,
            bank_account_id: item.bank_account_id ?? null,
            amount: item.amount,
            paid_at: item.paid_at,
        }),
    },
    tabs: [
        { name: 'form', label: 'Informações' },
        { name: 'file', label: 'Anexos' },
    ],
};

const {
    open,
    baseUrl,
    filters,
    search,
    clear,
    selectedItem,
    tabs,
    actions,
    handleAction,
    handleSave,
} = useCrud(workSchema);

const emit = defineEmits(['update:filters']);
const filtersProxy = computed({
    get: () => filters,
    set: (value) => emit('update:filters', value),
});

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
            <AppUploadModal label="Novo pagamento" :url="baseUrl" />
        </template>
        <div class="content-box">
            <FlashMessage />

            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filtersProxy"
                    @change="search"
                    @clear="clear"
                >
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
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <CrudTable
                :items="props.payments"
                :columns="columns"
                :actions="actions"
                @action="handleAction"
            />
            <CrudDrawer
                v-model:open="open"
                :tabs="tabs"
                :item="selectedItem"
                @save="handleSave"
            >
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
                            @created="
                                ({ field, value }) => (form[field] = value)
                            "
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

                        <AppInputMoney
                            v-model="form.amount"
                            :error="form.errors.amount"
                            label="Valor"
                        />

                        <AppInputDate
                            v-model="form.paid_at"
                            :error="form.errors.paid_at"
                            label="Pago em"
                        />
                    </AppFormLayout>
                </template>

                <template #file="{ item, form }">
                    <AppFileInput
                        :attachments="item?.attachments"
                        :form="form"
                    />
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
