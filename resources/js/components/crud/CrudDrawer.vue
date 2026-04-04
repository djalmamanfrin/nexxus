<script setup lang="ts">
import SidebarDrawer from '@/components/ui/sidebar/SidebarDrawer.vue';
import SidebarDrawerTabs from '@/components/ui/sidebar/SidebarDrawerTabs.vue';
import SidebarDrawerTab from '@/components/ui/sidebar/SidebarDrawerTab.vue';
import SidebarDrawerPanel from '@/components/ui/sidebar/SidebarDrawerPanel.vue';

const props = defineProps({
    open: Boolean,
    tabs: Array,
    item: Object,
});

const emit = defineEmits(['update:open', 'save']);
</script>

<template>
    <SidebarDrawer
        :open="open"
        @close="emit('update:open', false)"
        @save="emit('save')"
    >
        <template #title>
            <slot name="title" />
        </template>

        <SidebarDrawerTabs>
            <SidebarDrawerTab
                v-for="tab in props.tabs"
                :key="tab.name"
                :name="tab.name"
                :label="tab.label"
            />
        </SidebarDrawerTabs>

        <SidebarDrawerPanel
            v-for="tab in props.tabs"
            :key="tab.name"
            :name="tab.name"
        >
            <slot :name="tab.name" :item="item" :form="tab.form" />
        </SidebarDrawerPanel>
    </SidebarDrawer>
</template>
