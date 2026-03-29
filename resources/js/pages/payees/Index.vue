<script setup lang="ts">
import { type BreadcrumbItem, Payee } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppTextarea from '@/components/base/AppTextarea.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';

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
    { key: 'name', label: 'Nome Fantasia' },
    { key: 'document', label: 'CNPJ/CPF' },
    { key: 'document_type', label: 'Tipo de documento' },
    { key: 'is_pix_document', label: 'Pix documento?' },
    { key: 'pix_key', label: 'Chave pix' },
    { key: 'pix_key_type', label: 'Tipo de chave pix' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Beneficiário',
        href: '',
        btn: { label: 'Novo Beneficiário', url: url },
    },
];
</script>

<template>
    <CrudIndexPage
        :items="props.payees"
        :columns="columns"
        :base-url="url"
        :breadcrumbs="breadcrumbItems"
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

        <template #form="{ item, form }">
            <AppFormLayout :item="item">
                <AppInput
                    v-model="form.name"
                    :error="form.errors.name"
                    label="Name"
                />
                <AppInput
                    v-model="form.document_type"
                    :error="form.errors.document_type"
                    label="Tipo de documento"
                />
                <AppInput
                    v-model="form.document"
                    :error="form.errors.document"
                    label="CNPJ/CPF"
                />
                <AppSwitch
                    v-model="form.is_pix_document"
                    label="Usar documento como PIX"
                />
                <div v-if="!form.is_pix_document">
                    <AppInput
                        v-model="form.pix_key_type"
                        label="Tipo de chave Pix"
                        :error="form.errors.pix_key_type"
                    />
                    <AppInput
                        v-model="form.pix_key"
                        label="Chave pix"
                        :error="form.errors.pix_key"
                    />
                </div>
            </AppFormLayout>
        </template>
    </CrudIndexPage>
</template>
