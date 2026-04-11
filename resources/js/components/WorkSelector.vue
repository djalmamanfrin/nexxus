<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import AppButton from '@/components/AppButton.vue';
import axios from 'axios';
import { SelectOption } from '@/types/select';
import { onMounted, ref } from 'vue';
import { Building2Icon } from 'lucide-vue-next';
import { dashboard } from '@/routes';
import AppLogo from '@/components/AppLogo.vue';

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

const workSelectorOptions = ref<SelectOption[]>([]);
const workSelectorName = ref<string>();
const fetchOptions = async () => {
    try {
        const { data } = await axios.get<SelectOption[]>('works/options');
        workSelectorOptions.value = data;
        workSelectorName.value = data.find(
            (opt) => opt.value === activeWorkId,
        )?.label;
    } catch (e) {
        console.error(e);
    } finally {
    }
};

onMounted(fetchOptions);
</script>

<template>
    <AppButton
        type="link"
        href="/works"
        variant="secondary"
        :label="workSelectorName"
        :icon="Building2Icon"
        class="w-full"
    />
</template>
