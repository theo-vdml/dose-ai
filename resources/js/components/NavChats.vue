<script setup lang="ts">
    import {
        SidebarGroup,
        SidebarGroupLabel,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
    } from '@/components/ui/sidebar';
    import { urlIsActive } from '@/lib/utils';
    import conversationsRoutes from '@/routes/conversations';
    import { Link, usePage } from '@inertiajs/vue3';
    import { useEcho } from '@laravel/echo-vue';
    import { ref } from 'vue';

    const page = usePage();

    const conversations = ref(page.props.auth.conversations);

    const getConversationUrl = (id: string) => {
        return conversationsRoutes.show(id).url;
    };

    try {
        console.log('Echo: ', (window as any).Echo);
        console.log('VITE_REVERB_HOST: ', import.meta.env.VITE_REVERB_HOST);
        useEcho<{
            conversationId: string;
            title: string;
        }>(
            'Users.' + page.props.auth.user.id,
            'ConversationTitleGenerated',
            (e) => {
                const conversation = conversations.value.find(
                    (c) => c.id === e.conversationId
                );

                if (conversation) {
                    conversation.title = e.title;
                }
            }
        );
    } catch (e) {
        console.error('Error setting up Echo listener:', e);
    }

</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Conversations</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="c in conversations" :key="c.id">
                <SidebarMenuButton as-child :is-active="urlIsActive(getConversationUrl(c.id), page.url)"
                    :tooltip="c.title">
                    <Link :href="getConversationUrl(c.id)">
                        <!-- <component :is="c.icon" /> -->
                        <span>{{ c.title ?? 'Untitled' }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
