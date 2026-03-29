<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppUploadModal from '@/components/base/AppUploadModal.vue';
import AppContent from '@/components/AppContent.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent
            variant="sidebar"
            class="flex h-full min-h-0 flex-col overflow-hidden"
        >
            <header
                class="border-sidebar-border/70 group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 flex h-16 shrink-0 items-center justify-between gap-2 border-b px-6 transition-[width,height] ease-linear md:px-4"
            >
                <div class="flex items-center gap-2">
                    <SidebarTrigger class="-ml-1" />
                    <template v-if="breadcrumbs && breadcrumbs.length > 0">
                        <Breadcrumbs :breadcrumbs="breadcrumbs" />
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <slot name="header-actions" />
                </div>
            </header>
            <slot />
        </AppContent>
    </AppShell>
</template>
