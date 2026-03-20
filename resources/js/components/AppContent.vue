<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
import { computed } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
    class?: string;
}

const props = defineProps<Props>();
const className = computed(() => props.class);
</script>

<template>
    <SidebarInset
        v-if="props.variant === 'sidebar'"
        class="flex h-full min-h-0 flex-col overflow-hidden"
        :class="className"
    >
        <div class="min-h-0 flex-1 overflow-hidden">
            <slot />
        </div>
    </SidebarInset>

    <main
        v-else
        class="mx-auto flex h-full min-h-0 w-full max-w-7xl flex-1 flex-col gap-4 overflow-hidden rounded-xl"
        :class="className"
    >
        <div class="min-h-0 flex-1 overflow-hidden">
            <slot />
        </div>
    </main>
</template>
