<script lang="ts" setup>
    import { Conversation, ModelIndicator, PromptInput, StreamIndicator } from '@/components/chat';
    import { useCompletionStream } from '@/composables/useCompletionStream';
    import useMessages from '@/composables/useMessages';
    import AppLayout from '@/layouts/AppLayout.vue';
    import { Models } from '@/types/generated';
    import { useForm } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const props = defineProps<{
        conversation: Conversation;
        models: Models,
        model_id: string;
    }>();

    const form = useForm({
        model: props.model_id,
        message: '',
    })

    const { isFetching, isStreaming, contentBuffer, send } = useCompletionStream({
        conversationId: props.conversation.id,
        onAssistantMessageCreated: (message) => {
            addMessage(message);
        },
        onUserMessageCreated: (message) => {
            hydrateOptimisticMessage(message);
        },
    });

    const { messagesWithStreaming, addMessage, addOptimisticMessage, hydrateOptimisticMessage } = useMessages(props.conversation, contentBuffer);

    const disabled = computed(() => {
        return isFetching.value || isStreaming.value || form.message.trim() === '';
    })

    const cancelable = computed(() => {
        return isStreaming.value;
    })

    const submit = () => {
        addOptimisticMessage(form.message)
        send(form.message, form.model)
        form.message = '';
    }

</script>

<template>
    <AppLayout>
        <div class="relative h-[calc(100vh-4rem)]">
            <div class="h-full overflow-y-auto pb-48">
                <Conversation :messages="messagesWithStreaming" />
            </div>
            <div
                class="pointer-events-none absolute bottom-0 left-0 right-0 pb-8 pt-32 px-8 bg-linear-to-t from-background/95 via-60% via-background/50 to-transparent">
                <div class="mx-auto w-full max-w-5xl pointer-events-auto">
                    <PromptInput v-model:message="form.message" @submit="submit" :disabled :cancelable>
                        <ModelIndicator :label="model_id" />
                        <StreamIndicator :isFetching="isFetching" :isStreaming="isStreaming" />
                    </PromptInput>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
