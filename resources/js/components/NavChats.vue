<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { getPersonaIcon } from '@/lib/personaHelpers';
import { urlIsActive } from '@/lib/utils';
import conversationsRoutes from '@/routes/conversations';
import { Link, usePage } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import { MessageCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const page = usePage();

const conversations = ref(page.props.auth.conversations);

const getConversationUrl = (id: string) => {
    return conversationsRoutes.show(id).url;
};

useEcho<{
    conversationId: string;
    title: string;
}>('Users.' + page.props.auth.user.id, 'ConversationTitleUpdated', (e) => {
    const conversation = conversations.value.find((c) => c.id === e.conversationId);

    if (conversation) {
        conversation.title = e.title;
    }
});
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Conversations</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="c in conversations" :key="c.id">
                <SidebarMenuButton as-child :is-active="urlIsActive(getConversationUrl(c.id), page.url)" :tooltip="c.title ?? 'Untitled'">
                    <Link :href="getConversationUrl(c.id)">
                        <component :is="getPersonaIcon(c.persona_id, MessageCircle)" />
                        <span>{{ c.title ?? 'Untitled' }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
