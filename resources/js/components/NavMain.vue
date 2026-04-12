<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    CircleDollarSignIcon,
    Contact,
    DollarSign,
    HomeIcon,
    LucideFolderTree,
    UnplugIcon,
} from 'lucide-vue-next';
import { computed } from 'vue';

const page = usePage();
const activeWorkId = page.props.auth.user.active_work_id;

const items: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: HomeIcon,
        isActive: true,
    },
    {
        title: 'C. de Custos',
        href: '/cost-centers',
        icon: LucideFolderTree,
        isActive: !!activeWorkId,
    },
    {
        title: 'Pagamentos',
        href: '/payments',
        icon: DollarSign,
        isActive: !!activeWorkId,
    },
    {
        title: 'Despesas',
        href: '/expenses',
        icon: CircleDollarSignIcon,
        isActive: !!activeWorkId,
    },
    {
        title: 'Consiliação',
        href: '/reconciliation',
        icon: UnplugIcon,
        isActive: !!activeWorkId,
    },
    {
        title: 'Beneficiários',
        href: '/payees',
        icon: Contact,
        isActive: true,
    },
];

const reactiveItems = computed(() => items.filter((item) => item.isActive));
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Menu</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in reactiveItems" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="urlIsActive(item.href, page.url)"
                    :tooltip="item.title"
                >
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
