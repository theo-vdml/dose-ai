<script lang="ts" setup>
import { Conversation, ModelIndicator, PromptInput, StreamIndicator } from '@/components/chat';
import ScrollLockContainer from '@/components/ScrollLockContainer.vue';
import useChat from '@/composables/useChat';
import AppLayout from '@/layouts/AppLayout.vue';
import { getPersonaIcon, getPersonaName } from '@/lib/personaHelpers';
import conversations from '@/routes/conversations';
import { router } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import { MessageCircle, Trash2 } from 'lucide-vue-next';
import { nextTick, onMounted, ref } from 'vue';

const props = defineProps<{
    conversation: Conversation;
    model_id: string;
    messageValue?: string;
}>();

const chat = useChat(props.conversation);

const promptInput = ref<string>(props.messageValue ?? '');

const submit = () => {
    chat.sendMessage(promptInput.value);
    promptInput.value = '';
};

// --- Title Editing Logic ---
const isEditingTitle = ref(false);
const titleInput = ref(props.conversation.title || 'New Conversation');
const titleInputRef = ref<HTMLInputElement | null>(null);

const enableEdit = async () => {
    isEditingTitle.value = true;
    // Wait for DOM update so input exists before focusing
    await nextTick();
    titleInputRef.value?.focus();
};

const updateTitle = () => {
    isEditingTitle.value = false;
    console.log('Updating title to:', titleInput.value);

    // Only send request if changed
    if (titleInput.value !== props.conversation.title) {
        router.put(
            conversations.update(props.conversation.id),
            {
                title: titleInput.value,
            },
            {
                preserveScroll: true,
                preserveState: true,
            },
        );
    }
};

// --- Delete Logic ---
const deleteConversation = () => {
    if (confirm('Are you sure you want to delete this conversation?')) {
        router.delete(conversations.destroy(props.conversation.id));
    }
};

useEcho<{
    conversationId: string;
    title: string;
}>('Users.' + props.conversation.user_id, 'ConversationTitleUpdated', (e) => {
    if (e.conversationId === props.conversation.id) {
        titleInput.value = e.title;
    }
});

const isMounted = ref(false);

onMounted(() => {
    isMounted.value = true;
});

onMounted(async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const shouldSubmit = urlParams.get('submit');

    if (props.messageValue && shouldSubmit) {
        submit();

        // Clean up the URL by removing the query parameters
        router.replace({
            url: window.location.pathname,
            preserveState: true,
            preserveScroll: true,
        });
    }
});
</script>

<template>
    <AppLayout>
        <Teleport to="#header-teleport" v-if="isMounted">
            <div class="flex w-full items-center justify-between gap-2 pr-2">
                <div v-if="conversation.persona_id" class="flex items-center gap-2 rounded-md bg-neutral-800 px-2 py-1 text-muted-foreground">
                    <component :is="getPersonaIcon(conversation.persona_id, MessageCircle)" class="size-4" />
                    <span class="text-sm">
                        {{ getPersonaName(conversation.persona_id) }}
                    </span>
                </div>

                <div class="group min-w-0 flex-1">
                    <input
                        v-if="isEditingTitle"
                        ref="titleInputRef"
                        v-model="titleInput"
                        @blur="updateTitle"
                        @keydown.enter="updateTitle"
                        class="h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none"
                    />

                    <button
                        v-else
                        @click="enableEdit"
                        class="fit flex h-9 items-center truncate rounded-md px-2 text-left text-sm font-semibold hover:bg-accent hover:text-accent-foreground"
                        title="Click to rename"
                    >
                        {{ titleInput }}
                    </button>
                </div>

                <button
                    @click="deleteConversation"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive"
                    title="Delete conversation"
                >
                    <Trash2 class="h-4 w-4" />
                </button>
            </div>
        </Teleport>
        <div class="relative h-[calc(100vh-4rem)]">
            <ScrollLockContainer class="h-full overflow-y-auto pb-48" :starts-at-bottom="true" :smooth-scroll="true" :offset-bottom="25">
                <Conversation :messages="chat.messages.value" />
            </ScrollLockContainer>
            <div
                class="pointer-events-none absolute right-0 bottom-0 left-0 bg-linear-to-t from-background/95 via-background/50 via-60% to-transparent px-8 pt-32 pb-8"
            >
                <div class="pointer-events-auto mx-auto w-full max-w-5xl">
                    <PromptInput v-model:message="promptInput" @submit="submit" :disabled="chat.isBusy.value" :cancelable="chat.isBusy.value" disclaimer="AI can hallucinate or make mistakes. Do not share sensitive information.">
                        <ModelIndicator :label="model_id" />
                        <StreamIndicator :isFetching="chat.stream.isFetching.value" :isStreaming="chat.stream.isStreaming.value" />
                    </PromptInput>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
