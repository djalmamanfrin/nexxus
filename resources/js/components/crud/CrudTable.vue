<script setup lang="ts">
import AppTable, { Column } from '@/components/table/AppTable.vue';
import AppButton from '@/components/AppButton.vue';
import { EyeClosed } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';

defineProps<{
    items: Object;
    columns: Column[];
    actions?: Array<any>;
}>();

const page = usePage();
const activeWorkId = page.props.auth.user.active_work_id;

const emit = defineEmits(['action']);

function handleAction(action, item) {
    emit('action', { action, item });
}
</script>

<template>
    <AppTable :columns="columns" :items="items">
        <slot />

        <template v-if="actions?.length" #actions="{ item }">
            <AppButton
                v-for="action in actions"
                :key="action.name"
                :title="action.title"
                :icon="
                    action.name === 'see' && item.id !== activeWorkId
                        ? EyeClosed
                        : action.icon
                "
                variant="link"
                @click="handleAction(action.name, item)"
            />
        </template>
    </AppTable>
</template>
