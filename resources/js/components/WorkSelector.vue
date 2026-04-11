<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import AppButton from '@/components/AppButton.vue';
import axios from 'axios';
import { SelectOption } from '@/types/select';
import { onMounted, ref } from 'vue';
import { Building2Icon } from 'lucide-vue-next';
import { useSidebar } from '@/components/ui/sidebar';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';

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
    <TooltipProvider v-if="state === 'collapsed' || isMobile" :delay-duration="0">
        <Tooltip>
            <TooltipTrigger>
                <AppButton
                    type="link"
                    href="/works"
                    variant="secondary"
                    :icon="Building2Icon"
                    class="p-2"
                />
            </TooltipTrigger>
            <TooltipContent side="right" align="center">
                {{ workSelectorName }}
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
    <AppButton
        v-else
        type="link"
        href="/works"
        variant="secondary"
        :label="workSelectorName"
        :icon="Building2Icon"
        class="w-full"
    />
</template>
