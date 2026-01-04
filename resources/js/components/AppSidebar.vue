<script setup lang="ts">
    import NavFooter from '@/components/NavFooter.vue';
    import NavMain from '@/components/NavMain.vue';
    import NavUser from '@/components/NavUser.vue';
    import {
        Sidebar,
        SidebarContent,
        SidebarFooter,
        SidebarHeader,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
    } from '@/components/ui/sidebar';
    import { dashboard } from '@/routes';
    import { Link, usePage } from '@inertiajs/vue3';
    import AppLogo from './AppLogo.vue';
    import { mainNavigationItems, footerNavigationItems } from '@/navigation';
    import NavChats from './NavChats.vue';

</script>

<template>
    <Sidebar collapsible="icon" variant="floating">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavigationItems" />
            <NavChats />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavigationItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
