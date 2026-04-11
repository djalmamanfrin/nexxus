<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import AppButton from '@/components/AppButton.vue';
import axios from 'axios';
import { SelectOption } from '@/types/select';
import { onMounted, ref } from 'vue';
import { Building2Icon } from 'lucide-vue-next';
import {
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import Icon from '@/components/Icon.vue';
import { urlIsActive } from '@/lib/utils';

const page = usePage();
const activeWorkId = page.props.auth.user.active_work_id;
const emit = defineEmits(['update:modelValue']);
const { isMobile, state } = useSidebar();

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
const url = '/works';
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
