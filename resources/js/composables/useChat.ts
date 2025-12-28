import { ref } from 'vue';
import { useCompletionStream } from './useCompletionStream';

export default function useChat() {
    const messages = ref<Message[]>([]);

    const stream = useCompletionStream({
        conversationId: 1,
    });
}
