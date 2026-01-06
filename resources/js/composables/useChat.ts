import conversations from '@/routes/conversations';
import axios from 'axios';
import { computed, readonly, Ref, ref, shallowReactive } from 'vue';
import { useCompletionStream } from './useCompletionStream';

interface UseChatReturn {
    currentId: Ref<Message['id'] | null>;
    messagesMap: Ref<Map<Message['id'], Message>>;
    messages: Ref<Message[]>;
    isBusy: Ref<boolean>;
    isWaitingForFirstToken: Ref<boolean>;
    stream: ReturnType<typeof useCompletionStream>;
    sendMessage: (content: string) => Promise<void>;
}

export default function useChat(conversation: Conversation): UseChatReturn {
    const currentId = ref<Message['id'] | null>(conversation.current_message_id);
    const streamingMessageId = ref<Message['id'] | null>(null);
    const messagesMap = ref<Map<Message['id'], Message>>(new Map());
    const forceBusy = ref(false);

    const getMessage = (messageId: Message['id'] | null): Message | null => {
        if (!messageId) return null;
        return messagesMap.value.get(messageId) || null;
    };

    const addMessage = (message: Message, defineAsCurrent = false) => {
        messagesMap.value.set(message.id, message);
        if (defineAsCurrent) {
            currentId.value = message.id;
        }
    };

    const updateMessage = (payload: Partial<Message> & { id: Message['id'] }) => {
        const message = messagesMap.value.get(payload.id);
        if (!message) return;

        const updatedMessage = { ...message, ...payload };
        messagesMap.value.set(payload.id, updatedMessage);
    };

    const deleteMessage = (messageId: Message['id'], force: boolean = false) => {
        // Get the message to delete
        const messageToDelete = getMessage(messageId);
        if (!messageToDelete) return;

        // Prevent deleting the current message unless forced
        if (currentId.value === messageId && !force) {
            throw new Error('Cannot delete the current message without force flag.');
        }
        // Delete the message from the map
        messagesMap.value.delete(messageId);

        // If the deleted message was current
        // replace currentId with its parent
        // to maintain chat continuity
        if (currentId.value === messageId) {
            currentId.value = messageToDelete.parent_message_id;
        }
    };

    // Attach an error message to the current assistant message in the chat (or create a new one if none)
    const dispatchError = (errorMessage: string, replace: boolean = false) => {
        // Retrieve the current message
        const message = getMessage(currentId.value);

        // If no current message or it's not an assistant, create a new one
        if (!message || message.role !== 'assistant') {
            const tempMessage = createTemporaryMessage({
                role: 'assistant',
                content: '',
                error: errorMessage,
            });
            addMessage(tempMessage, true);
            return;
        }

        // Otherwise, update the existing message
        if (replace || !message.error) {
            updateMessage({
                id: message.id,
                error: errorMessage,
                state: 'failed',
            });
        }
    };

    const initializeMessages = (messages: Message[]) => {
        messagesMap.value.clear();

        for (const message of messages) {
            addMessage({
                ...message,
                state: 'completed',
            });
        }
    };

    initializeMessages(conversation.messages);

    const stream = useCompletionStream({
        conversationId: conversation.id,
        onCreated(message) {
            // Save the id to track which message is being streamed
            streamingMessageId.value = message.id;
            // Add the assistant message to the messages map
            addMessage({ ...message, state: 'pending' }, true);
        },
        onDelta(delta, type) {
            if (streamingMessageId.value) {
                // Retrieve the message being streamed
                const message = getMessage(streamingMessageId.value);
                if (!message) return;

                // Consume the delta based on its type
                if (type === 'content') {
                    message.content += delta;
                    message.state = 'answering';
                } else if (type === 'reasoning') {
                    message.reasoning = (message.reasoning || '') + delta;
                    message.state = 'reasoning';
                }

                // Update the message in the map
                updateMessage(message);
            }
        },
        onCompleted(message) {
            // Update the final message in the messages map
            updateMessage({
                ...message,
                state: 'completed',
            });
            // Clear the streaming message id
            streamingMessageId.value = null;
        },
        onError(error) {
            console.error('Streaming error:', error);
            // Attach the error to the current message
            dispatchError(error.message, true);
            // Clear the streaming message id
            streamingMessageId.value = null;
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

    const createTemporaryMessage = (payload: Partial<Message>): Message => {
        return {
            id: crypto.randomUUID(),
            parent_message_id: payload.parent_message_id || currentId.value || null,
            role: payload.role || 'user',
            content: payload.content || '',
            created_at: new Date().toString(),
            updated_at: new Date().toString(),
            reasoning: payload.reasoning || null,
            error: payload.error || null,
        };
    };

    const sendMessage = async (content: string) => {
        // Prevent sending if already busy
        if (isBusy.value) {
            return;
        }

        // Force busy state to prevent concurrent sends
        forceBusy.value = true;

        // Create local temporary user message
        const userMessage = createTemporaryMessage({
            role: 'user',
            content,
        });

        // Add the user message locally and define it as current
        addMessage(userMessage, true);

        try {
            try {
                // Retrive the endpoint to store the user message
                const endpoint = conversations.messages.store(conversation.id);
                // Build the payload to store the user message
                const payload = {
                    role: userMessage.role,
                    content: userMessage.content,
                };
                // Send the user message to the server
                const { data: serverUserMessage } = await axios.post<Message>(endpoint.url, payload);
                // Add the persisted user message (from server) to the messages map and define it as current
                addMessage(serverUserMessage, true);
                // Remove the temporary user message
                deleteMessage(userMessage.id);
            } catch (axiosError) {
                if (axios.isAxiosError(axiosError)) {
                    // Retrieve error message from server response
                    const errorMessage = axiosError.response?.data?.message || axiosError.message || 'Failed to send message.';
                    // Attach the error to the current message
                    dispatchError(errorMessage, true);
                }

                // Throw the error anyway to skip assistant message creation and streaming
                throw axiosError;
            }

            // Start the assistant message streaming
            stream.start();
        } catch (error) {
            // Attach generic error if not already attached
            dispatchError((error as any)?.message || 'An unexpected error occurred.');
        } finally {
            // Release busy state anyway
            forceBusy.value = false;
        }
    };

    const isBusy = computed(() => {
        return forceBusy.value || stream.isFetching.value || stream.isStreaming.value;
    });

    const isWaitingForFirstToken = computed(() => {
        if (!streamingMessageId.value) return false;

        const assistantMessage = messagesMap.value.get(streamingMessageId.value!);
        if (!assistantMessage) return false;

        return assistantMessage.content.length === 0 && (assistantMessage.reasoning || '').length === 0 && isBusy.value;
    });

    return shallowReactive({
        currentId,
        messagesMap,
        messages,
        sendMessage,
        isBusy: readonly(isBusy),
        isWaitingForFirstToken: readonly(isWaitingForFirstToken),
        stream,
    });
}
