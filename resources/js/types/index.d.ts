import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
    btn?: Object
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    active_work_id: string | null;
    created_at: string;
    updated_at: string;
}

export interface Attachment {
    id: number;
    original_name: string;
    url: string;
    mime_type: string;
}

export interface Payment {
    id: number;
    amount: string;
    paid_at: string;
    created_at: string;
    attachments: Attachment[];
    expense?: {
        id: number;
        name: string;
    } | null;
    bank_account?: {
        id: number;
        name: string;
    } | null;
    status?: {
        id: number;
        name: string;
        color: string;
    } | null;
    payment_type?: {
        id: number;
        name: string;
    } | null;
}

export interface Expense {
    id: number;
    reference: string;
    amount: string;
    due_at: string | null;
    competence_date: string | null;
    attachments: Attachment[];
    payee?: {
        id: number;
        name: string;
    } | null;
    cost_center?: {
        id: number;
        name: string;
    } | null;
    status?: {
        id: number;
        name: string;
        color: string;
    } | null;
}

export interface CostCenterType {
    id: number;
    name: string;
    code: string;
    is_active: string;
    description: string;
}
export interface CostCenter {
    id: number;
    code: string;
    description: string;
    budget: string;
    start_date: string | null;
    expected_end_date: string | null;
    created_at: string | null;
    work?: {
        id: number;
        name: string;
    } | null;
    type?: {
        id: number;
        name: string;
    } | null;
}

export interface Work {
    id: number;
    name: string;
    code: string;
    is_active: string;
    description: string;
    created_at: string;
}

export interface Payee {
    id: number;
    name: string;
    document: string;
    document_type: string;
    is_pix_document: boolean;
    pix_key: string;
    pix_key_type: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
