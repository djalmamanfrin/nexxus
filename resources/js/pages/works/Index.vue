<script setup lang="ts">
import { type BreadcrumbItem, Work } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import { useFilters } from '@/composables/useFilters';
import CrudIndexPage from '@/pages/CrudIndexPage.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppTextarea from '@/components/base/AppTextarea.vue';

const props = defineProps<{
    works: {
        data: Work[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const url = '/works';

const { filters, search, clear } = useFilters(
    {
        search_by: props.search_by || '',
    },
    url,
);

const columns = [
    { key: 'name', label: 'Obra' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Obras',
        href: '',
        btn: { label: 'Nova Obra', url: url },
    },
];
</script>

<template>
    <CrudIndexPage
        :items="props.works"
        :columns="columns"
        :base-url="url"
        :breadcrumbs="breadcrumbItems"
        v-model:filters="filters"
        :search="search"
        :clear="clear"
        :initialForm="{
            name: null,
            description: null,
        }"
        :mapToForm="
            (item) => ({
                name: item.name,
                description: item.description,
            })
        "
    >
        <template #filters>
            <FilterText
                label="Buscar Obra"
                name="search_by"
                placeholder="Pesquise pelo nome ou algo na descrição"
            />
        </template>

        <template #form="{ item, form }">
            <AppFormLayout :item="item">
                <AppInput v-model="form.name" label="Name" />
                <AppTextarea
                    v-model="form.description"
                    label="Descrição"
                    placeholder="Uma descrição que ajuda a identificar o centro de custo"
                />
            </AppFormLayout>
        </template>
    </CrudIndexPage>
</template>
