<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import Upload from '@/pages/attachments/Upload.vue';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="border-sidebar-border/70 group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 flex h-16 shrink-0 items-center justify-between gap-2 border-b px-6 transition-[width,height] ease-linear md:px-4"
    >
        <!-- Lado esquerdo -->
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <!-- Lado direito -->
        <div v-if="breadcrumbs && breadcrumbs[0].btn" class="flex items-center gap-2">
            <Upload :url="breadcrumbs[0].btn.url" :label="breadcrumbs[0].btn.label" />
        </div>
    </header>
</template>
