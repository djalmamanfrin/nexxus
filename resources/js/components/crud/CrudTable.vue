<script setup lang="ts">
import AppTable from '@/components/base/AppTable.vue';
import AppButton from '@/components/AppButton.vue';

defineProps<{
    items: Object;
    columns: Column[];
    actions: {
        type: Array;
        default: () => [];
    };
}>();

const emit = defineEmits(['action']);

function handleAction(action, item) {
    emit('action', { action, item });
}
</script>

<template>
    <AppTable :columns="columns" :items="items">
        <slot />

        <template #actions="{ item }">
            <AppButton
                v-for="action in actions"
                :key="action.name"
                :title="action.title"
                :icon="action.icon"
                variant="link"
                @click="handleAction(action.name, item)"
            />
        </template>
    </AppTable>
</template>
