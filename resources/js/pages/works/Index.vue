<script setup lang="ts">
import { computed, watch } from 'vue';
import { useCrud } from '@/composables/useCrud';
import { type BreadcrumbItem, CostCenter, Work } from '@/types';
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
import Fields from '@/pages/cost_centers/Fields.vue';
import { SelectOption } from '@/types/select';

const props = defineProps<{
    works: {
        data: Work[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    active_status: SelectOption[];
    is_active?: string;
    search_by?: string;
}>();

const columns = [
    { key: 'name', label: 'Obra', align: 'left' },
    { key: 'code', label: 'Código', type: 'badge' },
    {
        key: 'active.label',
        label: 'Status',
        type: 'badge',
        color: 'active.color',
    },
    { key: 'created_at.formatted', label: 'Criado em' },
];

const workSchema = {
    entity: 'works',
    filters: {
        search_by: props.search_by || null,
        is_active: props.is_active || null,
    },
    actions: [
        { name: 'edit', title: 'Editar', icon: PencilIcon },
        { name: 'delete', title: 'Excluir', icon: Trash2Icon },
    ],
    form: {
        initial: {
            name: null,
            code: null,
            is_active: null,
        },

        map: (item: any) => ({
            name: item.name,
            code: item.code,
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
        title: 'Obras',
        href: '',
    },
];

watch(
    () => props.works.data,
    (newWorks) => {
        if (!selectedItem.value) return;

        const updated = newWorks.find((w) => w.id === selectedItem.value.id);

        if (updated) {
            selectedItem.value = updated;
        }
    },
);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header-actions>
            <AppButtonWithModal label="Nova Obra">
                <template #default="{ close }">
                    <AppCreateModal
                        :url="baseUrl"
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
                        v-if="active_status?.length"
                        label="Ativo"
                        name="is_active"
                        :tabs="active_status"
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

                        <AppInput
                            v-model="form.code"
                            :error="form.errors.code"
                            label="Código"
                            maxlength="5"
                            :uppercase="true"
                        />

                        <AppSwitch v-model="form.is_active" label="Ativo" />
                    </AppFormLayout>
                </template>

                <template #cost_centers="{ form, item }">
                    <AppButtonWithModal
                        label="Novo C. de Custo"
                        title="Novo centro de custo"
                        description="Cadastre centros de custo para organizar suas despesas
                por categoria e entender melhor para onde seu dinheiro está indo.
                Isso permite análises mais claras e decisões mais assertivas."
                    >
                        <template #default="{ close }">
                            <AppCreateModal
                                url="cost-centers"
                                @success="close"
                                :initialData="{
                                    code: null,
                                    work_id: item.id,
                                    budget: null,
                                    cost_center_type_id: null,
                                    description: null,
                                }"
                            >
                                <template #fields="{ form }">
                                    <Fields :form="form" />
                                </template>
                            </AppCreateModal>
                        </template>
                    </AppButtonWithModal>
                    <div class="my-4"></div>
                    <CrudTable
                        :items="item.cost_centers"
                        :columns="[
                            {
                                key: 'type.code',
                                label: 'Código',
                                type: 'link',
                                href: (item) =>
                                    `/cost-centers?search_by=${item.code}`,
                            },
                            { key: 'budget.formatted', label: 'Orçamento' },
                            {
                                key: 'status.name',
                                label: 'Status',
                                type: 'badge',
                                color: 'status.color',
                            },
                        ]"
                    />
                </template>
            </CrudDrawer>
        </div>
    </AppLayout>
</template>
