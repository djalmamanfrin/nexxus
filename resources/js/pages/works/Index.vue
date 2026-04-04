<script setup lang="ts">
import { computed } from 'vue';
import { useCrud } from '@/composables/useCrud';
import { type BreadcrumbItem, Work } from '@/types';
import FilterText from '@/components/filters/FilterText.vue';
import AppFormLayout from '@/components/base/AppFormLayout.vue';
import AppInput from '@/components/base/AppInput.vue';
import AppSwitch from '@/components/base/AppSwitch.vue';
import FilterTabs from '@/components/filters/FilterTabs.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import AppButtonWithModal from '@/components/base/AppButtonWithModal.vue';
import AppCreateModal from '@/components/AppCreateModal.vue';
import CrudTable from '@/components/crud/CrudTable.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import CrudDrawer from '@/components/crud/CrudDrawer.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';

const props = defineProps<{
    works: {
        data: Work[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    search_by?: string;
}>();

const url = '/works';

const activeValues = [
    { label: 'Sim', value: 1 },
    { label: 'Não', value: 0 },
];

const columns = [
    { key: 'name', label: 'Obra' },
    { key: 'active.label', label: 'Status', type: 'badge', color: 'active.color' },
    { key: 'created_at', label: 'Criado em', type: 'datetime' },
];

const workSchema = {
    entity: 'works',
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
            is_active: null,
        },

        map: (item: any) => ({
            name: item.name,
            is_active: Boolean(item.is_active),
        }),
    },
    tabs: [
        { name: 'form', label: 'Informações' },
        { name: 'cost_centers', label: 'Centro de Custo' },
    ],
};

const {
    open,
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
        title: 'Obras',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <AppButtonWithModal label="Nova Obra">
                <template #default="{ close }">
                    <AppCreateModal
                        :url="url"
                        @success="close"
                        :initialData="{ name: null }"
                    >
                        <template #fields="{ form }">
                            <AppInput
                                v-model="form.name"
                                :error="form.errors.name"
                                maxlength="30"
                                placeholder="Nome da Obra"
                            />
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
                    <FilterTabs
                        v-if="activeValues?.length"
                        label="Ativo"
                        name="is_active"
                        :tabs="activeValues"
                    />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <CrudTable
                :items="props.works"
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
                <template #title> Obra </template>

                <template #form="{ form, item }">
                    <AppFormLayout :item="item">
                        <AppInput
                            v-model="form.name"
                            :error="form.errors.name"
                            label="Nome"
                        />

                        <AppSwitch v-model="form.is_active" label="Ativo" />
                    </AppFormLayout>
                </template>

                <template #cost_centers="{ form, item }">
                    <!-- upload futuro -->
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
