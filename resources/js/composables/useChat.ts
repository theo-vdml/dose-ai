import conversations from '@/routes/conversations';
import axios from 'axios';
import { computed, readonly, Ref, ref, shallowReactive } from 'vue';
import { useCompletionStream } from './useCompletionStream';

interface UseChatReturn {
    currentId: Ref<Message['id'] | null>;
    messagesMap: Ref<Map<Message['id'], Message>>;
    messages: Ref<Message[]>;
    sendMessage: (content: string) => Promise<void>;
    isBusy: Ref<boolean>;
    stream: ReturnType<typeof useCompletionStream>;
}

export default function useChat(conversation: Conversation): UseChatReturn {
    const currentId = ref<Message['id'] | null>(null);
    const streamingMessageId = ref<Message['id'] | null>(null);
    const messagesMap = ref<Map<Message['id'], Message>>(new Map());
    const forceBusy = ref(false);

    const initializeMessages = (messages: Message[]) => {
        messagesMap.value.clear();

        for (const message of messages) {
            messagesMap.value.set(message.id, message);
        }

        if (messages.length > 0) {
            const childrenSet = new Set(messages.map((m) => m.parent_message_id).filter((id): id is string => id !== null));

            const leafMessage = messages.find((m) => !childrenSet.has(m.id));

            currentId.value = (leafMessage?.id || messages.sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())[0].id) ?? null;
        }
    };

    initializeMessages(conversation.messages);

    const stream = useCompletionStream({
        conversationId: conversation.id,
        onDelta(delta, type) {
            if (streamingMessageId.value) {
                // Retrieve the message being streamed
                const message = messagesMap.value.get(streamingMessageId.value);
                if (!message) return;

                // Consume the delta based on its type
                if (type === 'content') {
                    message.content += delta;
                } else if (type === 'reasoning') {
                    message.reasoning = (message.reasoning || '') + delta;
                }

                // Update the message in the map
                messagesMap.value.set(streamingMessageId.value, message);
            }
        },
        onMessagePersisted: (msg) => {
            if (streamingMessageId.value) {
                // Retrieve the message being streamed
                const message = messagesMap.value.get(streamingMessageId.value);
                if (!message) return;

                // Update the message with persisted data
                messagesMap.value.set(msg.id, msg);
                currentId.value = msg.id;
                messagesMap.value.delete(streamingMessageId.value);
                streamingMessageId.value = null;
            }
        },
        onError(error) {
            if (streamingMessageId.value) {
                // Retrieve the message being streamed
                const message = messagesMap.value.get(streamingMessageId.value);
                if (!message) return;

                // Attach the error to the message
                message.error = error.message || 'An error occurred during streaming.';
                messagesMap.value.set(streamingMessageId.value, message);
                streamingMessageId.value = null;
            }
        },
    });

    const messages = computed(() => {
        const messagesList: Message[] = [];
        let messageId = currentId.value;

        while (messageId) {
            const message = messagesMap.value.get(messageId);
            if (!message) break;

            messagesList.unshift(message);
            messageId = message.parent_message_id;
        }

        return messagesList;
    });

    const sendMessage = async (content: string) => {
        if (isBusy.value) {
            console.warn('Cannot send message while another operation is in progress.');
            return;
        }

        // Force busy state to prevent concurrent sends
        forceBusy.value = true;

        let userMessage: Message | null = null;
        let persistedUserMessage: Message | null = null;
        let assistantMessage: Message | null = null;

        // Create temporary user message
        userMessage = {
            id: crypto.randomUUID(),
            parent_message_id: currentId.value,
            role: 'user',
            content,
            created_at: new Date().toString(),
            updated_at: new Date().toString(),
            reasoning: null,
        };

        // Store locally the temporary user message
        messagesMap.value.set(userMessage.id, userMessage);
        currentId.value = userMessage.id;

        try {
            // Persist user message
            try {
                const { data } = await axios.post<Message>(conversations.messages.store(conversation.id).url, { ...userMessage });

                persistedUserMessage = data;

                // Update the message in the map with persisted data
                messagesMap.value.set(persistedUserMessage.id, persistedUserMessage);
                currentId.value = persistedUserMessage.id;
                // Remove the temporary message
                messagesMap.value.delete(userMessage.id);
            } catch (axiosError) {
                // If an error occurs, attach the error message to the user message
                if (axios.isAxiosError(axiosError)) {
                    const serverMessage = axiosError.response?.data.message || axiosError.message || 'An error occurred while sending the message.';

                    userMessage.error = serverMessage;
                    messagesMap.value.set(userMessage.id, userMessage);
                }

                // Throw the error anyway to skip assistant message creation and streaming
                throw axiosError;
            }

            // Create temporary assistant message
            assistantMessage = {
                id: crypto.randomUUID(),
                parent_message_id: persistedUserMessage.id,
                role: 'assistant',
                content: '',
                created_at: new Date().toString(),
                updated_at: new Date().toString(),
                reasoning: null,
            };

            // Store locally the temporary assistant message
            messagesMap.value.set(assistantMessage.id, assistantMessage);
            currentId.value = assistantMessage.id;

            // Start streaming completion for the assistant message
            streamingMessageId.value = assistantMessage.id;
            stream.start(assistantMessage);
        } catch (error) {
            if (userMessage && !userMessage.error) {
                userMessage.error = 'An unexpected error occurred.';
                messagesMap.value.set(userMessage.id, userMessage);
            }
        } finally {
            // Release busy state
            forceBusy.value = false;
        }
    };

    const isBusy = computed(() => {
        return forceBusy.value || stream.isFetching.value || stream.isStreaming.value;
    });

    return shallowReactive({
        currentId,
        messagesMap,
        messages,
        sendMessage,
        isBusy: readonly(isBusy),
        stream,
    });
}
