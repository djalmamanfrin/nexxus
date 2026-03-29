<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import AppTable, { Column } from '@/components/base/AppTable.vue';
import AppFilterBar from '@/components/filters/AppFilterBar.vue';
import SidebarDrawer from '@/components/ui/sidebar/SidebarDrawer.vue';
import SidebarDrawerTabs from '@/components/ui/sidebar/SidebarDrawerTabs.vue';
import SidebarDrawerTab from '@/components/ui/sidebar/SidebarDrawerTab.vue';
import SidebarDrawerPanel from '@/components/ui/sidebar/SidebarDrawerPanel.vue';
import AppButton from '@/components/AppButton.vue';
import { PencilIcon, Trash2Icon } from 'lucide-vue-next';
import FlashMessage from '@/components/FlashMessage.vue';

interface Item {
    id: number;
    [key: string]: any;
}

const props = defineProps<{
    items: {
        data: Item[];
        meta?: any;
    };
    columns: Column[];
    baseUrl: string;

    breadcrumbs: any[];

    filters: any;
    search: Function;
    clear: Function;

    mapToForm: (item: Item) => Record<string, any>;
    initialForm: Record<string, any>;
}>();

const open = ref(false);
const selectedItem = ref<Item | null>(null);
const emit = defineEmits(['update:filters']);

const filtersProxy = computed({
    get: () => props.filters,
    set: (value) => emit('update:filters', value),
});

const dataForm = useForm({ ...props.initialForm });
const fileForm = useForm({
    attachment: null,
});

const handleEdit = (item: Item) => {
    selectedItem.value = item;

    dataForm.defaults(props.mapToForm(item));
    dataForm.reset();

    fileForm.reset();

    open.value = true;
};

const handleDelete = (item: Item) => {
    if (!confirm('Tem certeza que deseja excluir?')) return;

    router.delete(`${props.baseUrl}/${item.id}`, {
        preserveScroll: true,
    });
};

const handleSave = () => {
    if (!selectedItem.value) return;

    const id = selectedItem.value.id;

    if (dataForm.isDirty) {
        dataForm.patch(`${props.baseUrl}/${id}`, {
            preserveState: true,
            onSuccess: () => {
                open.value = false;
                dataForm.reset();
            },
        });
    }

    if (fileForm.isDirty) {
        fileForm.post(`${props.baseUrl}/${id}/attachments`, {
            forceFormData: true,
            preserveState: true,
            onSuccess: () => {
                open.value = false;
                fileForm.reset();
            },
        });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="content-box">
            <FlashMessage/>
            <form @submit.prevent="search">
                <AppFilterBar
                    v-model:filters="filtersProxy"
                    @change="search"
                    @clear="clear"
                >
                    <slot name="filters" />
                </AppFilterBar>
            </form>

            <div class="my-4"></div>

            <AppTable :columns="columns" :items="items">
                <template #cell-attachments="{ item }">
                    <span v-if="item.attachments?.length">
                        {{ item.attachments[0].original_name }}
                    </span>
                    <span v-else>-</span>
                </template>

                <template #cell-status_name="{ item }">
                    <span
                        :class="[
                            'rounded px-2 py-1 text-xs',
                            `bg-${item.status?.color}-100 text-${item.status?.color}-800`,
                        ]"
                    >
                        {{ item.status?.name || '-' }}
                    </span>
                </template>
                <template #actions="{ item }">
                    <AppButton
                        @click="handleEdit(item)"
                        title="Editar"
                        variant="link"
                        :icon="PencilIcon"
                    />
                    <AppButton
                        @click="handleDelete(item)"
                        title="Excluir"
                        variant="link"
                        :icon="Trash2Icon"
                    />
                </template>
            </AppTable>
        </div>

        <SidebarDrawer :open="open" @close="open = false" @save="handleSave">
            <template #title>
                <slot name="title" />
            </template>

            <SidebarDrawerTabs>
                <SidebarDrawerTab name="info" label="Informações" />
                <SidebarDrawerTab
                    v-if="$slots.file"
                    name="arquivo"
                    label="Arquivo"
                />
            </SidebarDrawerTabs>

            <SidebarDrawerPanel name="info">
                <slot name="form" :item="selectedItem" :form="dataForm" />
            </SidebarDrawerPanel>

            <SidebarDrawerPanel v-if="$slots.file" name="arquivo">
                <slot name="file" :item="selectedItem" :form="fileForm" />
            </SidebarDrawerPanel>
        </SidebarDrawer>
    </AppLayout>
</template>
