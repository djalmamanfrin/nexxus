<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { SelectOption } from '@/types/select';
import { onMounted, ref } from 'vue';
import { Building2Icon } from 'lucide-vue-next';
import { SidebarMenuButton } from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';

const page = usePage();
const activeWorkId = page.props.auth.user.active_work_id;
const emit = defineEmits(['update:modelValue']);

const url = '/works';
const workSelectorOptions = ref<SelectOption[]>([]);
const workSelectorName = ref<string>();
const fetchOptions = async () => {
    try {
        const { data } = await axios.get<SelectOption[]>('works/options');
        workSelectorOptions.value = data;
        workSelectorName.value = data.find(
            (opt) => opt.value === activeWorkId,
        )?.label || 'Ver Obras';
    } catch (e) {
        console.error(e);
    } finally {
    }
};

onMounted(fetchOptions);
</script>

<template>
    <SidebarMenuButton
        as-child
        :is-active="urlIsActive(url, page.url)"
        :tooltip="workSelectorName"
    >
        <Link :href="url">
            <component :is="Building2Icon" />
            <span>{{ workSelectorName }}</span>
        </Link>
    </SidebarMenuButton>
</template>
