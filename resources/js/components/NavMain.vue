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
    Building2,
    CircleDollarSignIcon,
    Contact,
    DollarSign,
    HomeIcon,
    LucideFolderTree,
} from 'lucide-vue-next';

const items: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: HomeIcon,
    },
    {
        title: 'Obras',
        href: '/works',
        icon: Building2,
    },
    {
        title: 'C. de Custos',
        href: '/cost-centers',
        icon: LucideFolderTree,
    },
    {
        title: 'Pagamentos',
        href: '/payments',
        icon: DollarSign,
    },
    {
        title: 'Despesas',
        href: '/expenses',
        icon: CircleDollarSignIcon,
    },
    {
        title: 'Beneficiários',
        href: '/payees',
        icon: Contact,
    },
];

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Menu</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
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
