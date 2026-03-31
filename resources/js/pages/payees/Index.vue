<script setup lang="ts">
import { type BreadcrumbItem, Payee } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import AppButtonWithModal from '@/components/base/AppButtonWithModal.vue';
import CreatePayee from '@/pages/payees/CreatePayee.vue';
import AppCreateModal from '@/components/AppCreateModal.vue';
import Fields from '@/pages/payees/Fields.vue';

const props = defineProps<{
    payees: {
        data: Payee[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const url = '/payees';

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
    },
    url,
);

const columns = [
    { key: 'name', label: 'Nome Fantasia', align: 'left' },
    { key: 'document_formatted', label: 'CNPJ/CPF' },
    { key: 'active.label', label: 'Pix documento?' },
    { key: 'pix_key', label: 'Chave pix' },
    { key: 'pix_key_type', label: 'Tipo de chave pix' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

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
                        :url="url"
                        @success="close"
                        :initialData="{
                            name: null,
                            document: null,
                            is_pix_document: null,
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
        <CrudIndexPage
            :items="props.payees"
            :columns="columns"
            :base-url="url"
            v-model:filters="filters"
            :search="search"
            :clear="clear"
            :initialForm="{
                name: null,
                document: null,
                document_type: null,
                is_pix_document: null,
                pix_key: null,
                pix_key_type: null,
            }"
            :mapToForm="
                (item) => ({
                    name: item.name,
                    document: item.document,
                    document_type: item.document_type,
                    is_pix_document: item.is_pix_document,
                    pix_key: item.pix_key,
                    pix_key_type: item.pix_key_type,
                })
            "
        >
            <template #filters>
                <FilterText
                    label="Buscar Beneficiário"
                    name="search_by"
                    placeholder="Pesquise pelo nome ou CPF, CNPJ"
                />
            </template>

            <template #title> Beneficiário </template>

            <template #form="{ item, form }">
                <AppFormLayout :item="item">
                    <Fields :form="form" />
                </AppFormLayout>
            </template>
        </CrudIndexPage>
    </AppLayout>
</template>
