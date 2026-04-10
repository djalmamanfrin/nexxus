<script setup lang="ts">
import AppSelect from '@/components/base/AppSelect.vue';
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();
const activeWorkId = page.props.auth.user.active_work_id;
const emit = defineEmits(['update:modelValue']);

function handleChange(workId: string | number | null) {
    emit('update:modelValue', workId);

    if (!workId) return;

    router.post(
        'user/active-work',
        {
            work_id: workId,
        },
        {
            preserveState: false,
            preserveScroll: true,
        },
    );
}
</script>

<template>
    <AppSelect
        url="works/options"
        :model-value="activeWorkId"
        @update:modelValue="handleChange"
        width="w-64"
    />
</template>
