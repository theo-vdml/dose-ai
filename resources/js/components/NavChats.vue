<script setup lang="ts">
    import {
        SidebarGroup,
        SidebarGroupLabel,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
    } from '@/components/ui/sidebar';
    import { urlIsActive } from '@/lib/utils';
    import conversations from '@/routes/conversations';
    import { Link, usePage } from '@inertiajs/vue3';
    import { useEcho } from '@laravel/echo-vue';

    const page = usePage();

    const chats = page.props.auth.conversations;

    const getConversationUrl = (chat: { id: string }) => {
        return conversations.show(chat.id).url;
    };

    useEcho(
        'users.' + page.props.auth.user.id,
        'conversation.title.generated',
        (e: { conversationId: string, title: string }) => {
            const chat = chats.find(c => c.id.toString() === e.conversationId.toString());
            if (chat) {
                chat.title = e.title;
            }
        }
    )

</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Conversations</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="chat in chats" :key="chat.id">
                <SidebarMenuButton as-child :is-active="urlIsActive(getConversationUrl(chat), page.url)"
                    :tooltip="chat.title">
                    <Link :href="getConversationUrl(chat)">
                        <!-- <component :is="chat.icon" /> -->
                        <span>{{ chat.title ?? 'Untitled' }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
