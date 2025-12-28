<script lang="ts" setup>
    import { Conversation, ModelIndicator, PromptInput, StreamIndicator } from '@/components/chat';
    import { useCompletionStream } from '@/composables/useCompletionStream';
    import { useFlash } from '@/composables/useFlash';
    import useMessages from '@/composables/useMessages';
    import AppLayout from '@/layouts/AppLayout.vue';
    import { Models } from '@/types/generated';
    import { useForm } from '@inertiajs/vue3';
    import { computed, nextTick, onMounted } from 'vue';

    const props = defineProps<{
        conversation: Conversation;
        models: Models,
        model_id: string;
    }>();

    const { flash } = useFlash();

    const form = useForm({
        message: '',
    })

    const { isFetching, isStreaming, contentBuffer, reasoningBuffer, send } = useCompletionStream({
        conversationId: props.conversation.id,
        onAssistantMessageCreated: (message) => {
            addMessage(message);
        },
        onUserMessageCreated: (message) => {
            hydrateOptimisticMessage(message);
        },
    });

    const { messagesWithStreaming, addMessage, addOptimisticMessage, hydrateOptimisticMessage } = useMessages(props.conversation, contentBuffer, reasoningBuffer);

    const disabled = computed(() => {
        return isFetching.value || isStreaming.value || form.message.trim() === '';
    })

    const submit = () => {
        addOptimisticMessage(form.message)
        send(form.message)
        form.message = '';
    }

    onMounted(() => {
        nextTick(() => {
            if (flash.value.initialMessage) {
                addOptimisticMessage(flash.value.initialMessage);
                send(flash.value.initialMessage);
            }
        });
    })

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
                    <PromptInput v-model:message="form.message" @submit="submit" :disabled :cancelable="isStreaming">
                        <ModelIndicator :label="model_id" />
                        <StreamIndicator :isFetching="isFetching" :isStreaming="isStreaming" />
                    </PromptInput>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
