<script setup lang="ts">
import { type BreadcrumbItem, Payee } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AppButtonWithModal from '@/components/base/AppButtonWithModal.vue';
import AppCreateModal from '@/components/AppCreateModal.vue';
import Fields from '@/pages/payees/Fields.vue';
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import { useCrud } from '@/composables/useCrud';
import { computed } from 'vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import CrudTable from '@/components/crud/CrudTable.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import CrudDrawer from '@/components/crud/CrudDrawer.vue';

const props = defineProps<{
    payees: {
        data: Payee[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const columns = [
    { key: 'name', label: 'Nome Fantasia', align: 'left' },
    { key: 'document.formatted', label: 'CNPJ/CPF' },
    { key: 'active.label', label: 'Pix documento?', type: 'badge', color: 'active.color' },
    { key: 'pix_key.formatted', label: 'Chave pix' },
    { key: 'created_at', label: 'Criado em' },
];

const workSchema = {
    entity: 'payees',
    filters: {
        search_by: props.search_by || '',
    },
    actions: [
        { name: 'edit', title: 'Editar', icon: PencilIcon },
        { name: 'delete', title: 'Excluir', icon: Trash2Icon },
    ],
    form: {
        initial: {
            name: null,
            document: null,
            document_type: null,
            is_pix_document: null,
            pix_key: null,
            pix_key_type: null,
        },

        map: (item: any) => ({
            name: item.name,
            document: item.document.value,
            document_type: item.document_type,
            is_pix_document: item.is_pix_document,
            pix_key: item.pix_key?.value,
            pix_key_type: item.pix_key_type,
        }),
    },
    tabs: [
        { name: 'form', label: 'Informações' }
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
        title: 'Beneficiário',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <AppButtonWithModal
                label="Novo Beneficiário"
                title="Novo Beneficiário"
                description="Cadastre os beneficiários para identificar de quem
                você está comprando ou pagando. Isso permite acompanhar com quais
                 empresas ou pessoas você mais gasta e manter seus registros
                 organizados"
            >
                <template #default="{ close }">
                    <AppCreateModal
                        :url="baseUrl"
                        @success="close"
                        :initialData="{
                            name: null,
                            document: null,
                            is_pix_document: Boolean(0),
                            pix_key: null,
                        }"
                    >
                        <template #fields="{ form }">
                            <Fields :form="form" />
                        </template>
                    </AppCreateModal>
                </template>
            </AppButtonWithModal>
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
                        label="Buscar Obra"
                        name="search_by"
                        placeholder="Pesquise pelo nome ou algo na descrição"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <CrudTable
                :items="props.payees"
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
                <template #title> Beneficiário </template>

                <template #form="{ item, form }">
                    <AppFormLayout :item="item">
                        <Fields :form="form" />
                    </AppFormLayout>
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
