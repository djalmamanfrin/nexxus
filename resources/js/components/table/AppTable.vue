<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppBadge from '@/components/table/AppBadge.vue';
import AppAttachment from '@/components/table/AppAttachment.vue';
import TextLink from '@/components/TextLink.vue';
export interface Column {
    key: string;
    label: string;
    color?: string;
    align?: 'left' | 'center' | 'right';
    type?: 'attachment' | 'badge' | 'link';
    href?: string | ((item: any) => string);
}

const props = defineProps<{
    columns: Column[];
    items: {
        data: any[];
        meta?: any;
    };
}>();

// dot notation: status.name
const getValue = (obj: any, path: string) => {
    if (!obj || !path) return null;

    return path.split('.').reduce((acc, key) => acc?.[key], obj);
};
const getSlotName = (key: string) => {
    return `cell-${key.replace(/\./g, '_')}`;
};

const getAlignClass = (align: string) => {
    switch (align) {
        case 'left':
            return 'text-left';
        case 'right':
            return 'text-right';
        default:
            return 'text-center';
    }
};

const getHref = (column, item) => {
    if (typeof column.href === 'function') {
        return column.href(item);
    }

    return column.href;
};
</script>

<template>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr class="table-header">
                    <th
                        v-for="column in columns"
                        :key="column.key"
                        class="table-row-header"
                        :class="getAlignClass(column.align)"
                    >
                        {{ column.label }}
                    </th>
                    <th
                        v-if="$slots.actions"
                        class="table-row-header text-center"
                    >
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="item in items.data"
                    :key="item.id"
                    class="table-body"
                >
                    <td
                        v-for="column in columns"
                        :key="column.key"
                        class="table-row-body"
                        :class="getAlignClass(column.align)"
                    >
                        <AppAttachment
                            v-if="column.type === 'attachment'"
                            :attachments="item[column.key]"
                        />
                        <AppBadge
                            v-else-if="column.type === 'badge'"
                            :color="getValue(item, column.color)"
                            :label="getValue(item, column.key)"
                        />
                        <TextLink
                            :href="getHref(column, item)"
                            v-else-if="column.type === 'link'"
                            :label="getValue(item, column.key)"
                        >
                            {{ getValue(item, column.key) }}
                        </TextLink>
                        <slot
                            v-else
                            :name="getSlotName(column.key)"
                            :item="item"
                        >
                            {{ getValue(item, column.key) }}
                        </slot>
                    </td>
                    <td
                        v-if="$slots.actions"
                        class="table-actions table-actions-align"
                    >
                        <slot name="actions" :item="item" />
                    </td>
                </tr>
                <tr v-if="!items.data?.length">
                    <td
                        :colspan="columns.length + ($slots.actions ? 1 : 0)"
                        class="py-4 text-center text-gray-500"
                    >
                        Nenhum registro encontrado
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination v-if="items.meta?.links" :links="items.meta.links" />
    </div>
</template>
