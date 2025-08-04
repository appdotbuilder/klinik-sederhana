import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { Activity, Clipboard, CreditCard, LayoutGrid, Package, Pill, Users } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Pasien',
        href: '/patients',
        icon: Users,
    },
    {
        title: 'Kunjungan',
        href: '/visits',
        icon: Activity,
    },
    {
        title: 'Resep',
        href: '/prescriptions',
        icon: Pill,
    },
    {
        title: 'Tagihan',
        href: '/bills',
        icon: CreditCard,
    },
    {
        title: 'Inventori',
        href: '/inventory',
        icon: Package,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Tentang Sistem',
        href: '/',
        icon: Clipboard,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
