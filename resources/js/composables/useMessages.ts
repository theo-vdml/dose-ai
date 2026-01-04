import { computed, Ref, ref } from 'vue';

type MessageFactoryData = Partial<Message> & Pick<Message, 'id' | 'content'>;

export default function useMessages(
    conversation: Conversation,
    streamingContent?: Ref<string>,
    streamingReasoning?: Ref<string>,
) {
    const messages = ref<Message[]>(conversation.messages);

    const messageFactory = (data: MessageFactoryData): Message => {
        return {
            id: data.id,
            conversation_id: data.conversation_id || conversation?.id,
            parent_message_id: data.parent_message_id || undefined,
            role: data.role || 'user',
            content: data.content,
            reasoning: data.reasoning || undefined,
            created_at: data.created_at || new Date().toISOString(),
            updated_at: data.updated_at || new Date().toISOString(),
        };
    };

    const addMessage = (data: MessageFactoryData) => {
        clearOptimisticMessage();
        messages.value.push(messageFactory(data));
    };

    const addOptimisticMessage = (content: string, reasoning?: string) => {
        addMessage({
            id: -1,
            content,
            reasoning,
        });
    };

    const hydrateOptimisticMessage = (data: Partial<Message>) => {
        const index = messages.value.findIndex((msg) => msg.id === -1);
        if (index !== -1) {
            messages.value[index] = {
                ...messages.value[index],
                ...data,
            } as Message;
        }
    };

    const clearOptimisticMessage = () => {
        const index = messages.value.findIndex((msg) => msg.id === -1);
        if (index !== -1) {
            messages.value.splice(index, 1);
        }
    };

    const messagesWithStreaming = computed(() => {
        if (!streamingContent?.value && !streamingReasoning?.value) {
            return messages.value;
        }

        const streamedMessage = messageFactory({
            id: -2,
            role: 'assistant',
            content: streamingContent?.value || '',
            reasoning: streamingReasoning?.value || undefined,
        });

        return [...messages.value, streamedMessage];
    });

    return {
        messages,
        messagesWithStreaming,
        addMessage,
        addOptimisticMessage,
        hydrateOptimisticMessage,
        clearOptimisticMessage,
    };
}
