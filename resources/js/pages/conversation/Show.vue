<script lang="ts" setup>
import { Conversation, ModelIndicator, PromptInput, StreamIndicator } from '@/components/chat';
import ScrollLockContainer from '@/components/ScrollLockContainer.vue';
import useChat from '@/composables/useChat';
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

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
        <div class="relative h-[calc(100vh-4rem)]">
            <ScrollLockContainer class="h-full overflow-y-auto pb-48" :starts-at-bottom="true" :smooth-scroll="true" :offset-bottom="25">
                <Conversation :messages="chat.messages.value" />
            </ScrollLockContainer>
            <div
                class="pointer-events-none absolute right-0 bottom-0 left-0 bg-linear-to-t from-background/95 via-background/50 via-60% to-transparent px-8 pt-32 pb-8"
            >
                <div class="pointer-events-auto mx-auto w-full max-w-5xl">
                    <PromptInput v-model:message="promptInput" @submit="submit" :disabled="chat.isBusy.value" :cancelable="chat.isBusy.value">
                        <ModelIndicator :label="model_id" />
                        <StreamIndicator :isFetching="chat.stream.isFetching.value" :isStreaming="chat.stream.isStreaming.value" />
                    </PromptInput>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
