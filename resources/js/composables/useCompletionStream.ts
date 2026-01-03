/**
 * Composable for managing AI completion streams with support for content, reasoning, and usage data.
 * Handles SSE (Server-Sent Events) streaming from the backend API.
 */
import conversations from '@/routes/conversations';
import { useStream } from '@laravel/stream-vue';
import { readonly, ref, Ref, shallowReactive } from 'vue';

/**
 * Configuration options for the completion stream
 */
interface UseCompletionStreamOptions {
    /** The ID of the conversation to stream completions for */
    conversationId: string;
    /** Callback invoked when the stream completes successfully */
    onComplete?: (data: {
        content: string;
        reasoning: string;
        finishReason: string | null;
        usage: {
            promptTokens: number;
            completionTokens: number;
            totalTokens: number;
        } | null;
    }) => void;
    /** Callback invoked when an error occurs during streaming */
    onError?: (error: Error) => void;
    onChunk?: (chunk: SSEChunk) => void;
    onDelta?: (delta: string, type: 'content' | 'reasoning') => void;
    onMessagePersisted?: (message: Message) => void;
}

/**
 * Return type for the useCompletionStream composable
 * Provides reactive state and methods for controlling the completion stream
 */
interface UseCompletionStreamReturn {
    /** Raw stream data from the SSE connection */
    data: Readonly<Ref<string>>;
    /** Whether the stream is currently fetching */
    isFetching: Readonly<Ref<boolean>>;
    /** Whether data is actively being streamed */
    isStreaming: Readonly<Ref<boolean>>;
    /** Whether the AI is currently in reasoning mode */
    isReasoning: Readonly<Ref<boolean>>;
    /** Accumulated content (assistant response) from the stream */
    contentBuffer: Readonly<Ref<string>>;
    /** Accumulated reasoning data from the stream */
    reasoningBuffer: Readonly<Ref<string>>;
    /** The reason the stream finished (e.g., model name) */
    finishReason: Readonly<Ref<string | null>>;
    /** Token usage statistics for the completion */
    usage: Readonly<
        Ref<{
            promptTokens: number;
            completionTokens: number;
            totalTokens: number;
        } | null>
    >;
    /** Error object if the stream encounters an error */
    error: Readonly<Ref<Error | null>>;
    /** Send a message to start streaming a completion */
    start: (message: Message) => void;
    /** Cancel the active stream */
    cancel: () => void;
    /** Reset all stream state to initial values */
    reset: () => void;
}

/**
 * Union type representing the different types of SSE chunks that can be received from the stream.
 * Each chunk type has specific data associated with it.
 */
type SSEChunk =
    | {
          /** Content or reasoning delta chunk */
          type: 'content' | 'reasoning';
          data: {
              delta: string;
          };
      }
    | {
          /** Finish reason chunk indicating stream completion */
          type: 'finish_reason';
          data: {
              router: string;
              model: string;
          };
      }
    | {
          /** Token usage statistics chunk */
          type: 'usage';
          data: {
              prompt_tokens: number;
              completion_tokens: number;
              total_tokens: number;
          };
      }
    | {
          type: 'message_persisted';
          data: {
              message: Message;
          };
      };

/**
 * Composable for managing AI completion streams with real-time updates.
 * Processes SSE chunks and maintains state for content, reasoning, and usage data.
 *
 * @param options - Configuration options including conversation ID and callbacks
 * @returns Reactive state and control methods for the completion stream
 */
export function useCompletionStream(options: UseCompletionStreamOptions): UseCompletionStreamReturn {
    // Reactive state for accumulating streamed content
    const contentBuffer = ref('');
    const reasoningBuffer = ref('');
    const isReasoning = ref(false);
    const finishReason = ref<string | null>(null);
    const usage = ref<{
        promptTokens: number;
        completionTokens: number;
        totalTokens: number;
    } | null>(null);
    const error = ref<Error | null>(null);

    /**
     * Processes raw SSE chunks and updates the appropriate state based on chunk type.
     * Handles multiple events in a single chunk and parses JSON data.
     */
    const onSSEChunk = (rawChunk: string) => {
        // Split by double newline (SSE event separator)
        const events = rawChunk.split('\n\n').filter((line) => line.trim().length > 0);

        for (const event of events) {
            // Remove 'data: ' prefix from SSE event
            const jsonString = event.replace(/^data:\s*/, '').trim();

            // Skip empty events and completion markers
            if (!jsonString || jsonString === '[DONE]') {
                continue;
            }

            try {
                const chunk = JSON.parse(jsonString) as SSEChunk;
                if (options.onChunk) {
                    options.onChunk(chunk);
                }
                // Update reasoning state based on chunk type
                isReasoning.value = chunk.type === 'reasoning';
                switch (chunk.type) {
                    case 'content':
                        // Append content delta to the buffer
                        contentBuffer.value += chunk.data.delta;
                        if (options.onDelta) {
                            options.onDelta(chunk.data.delta, 'content');
                        }
                        break;
                    case 'reasoning':
                        // Append reasoning delta to the buffer
                        reasoningBuffer.value += chunk.data.delta;
                        if (options.onDelta) {
                            options.onDelta(chunk.data.delta, 'reasoning');
                        }
                        break;
                    case 'finish_reason':
                        // Store the model that finished the completion
                        finishReason.value = chunk.data.model;
                        break;
                    case 'usage':
                        // Store token usage statistics
                        usage.value = {
                            promptTokens: chunk.data.prompt_tokens,
                            completionTokens: chunk.data.completion_tokens,
                            totalTokens: chunk.data.total_tokens,
                        };
                        break;
                    case 'message_persisted':
                        if (options.onMessagePersisted) {
                            options.onMessagePersisted(chunk.data.message);
                        }
                        break;
                }
            } catch (e) {
                // Log parsing errors and continue processing remaining events
                console.error('Failed to parse SSE event:', e, jsonString);
                continue;
            }
        }
    };

    /**
     * Resets all stream state to initial values.
     * Should be called before starting a new stream.
     */
    const reset = () => {
        console.log('Resetting completion stream state');
        contentBuffer.value = '';
        reasoningBuffer.value = '';
        isReasoning.value = false;
        finishReason.value = null;
        usage.value = null;
        error.value = null;
    };

    // Retrieve the stream endpoint for the given conversation ID
    const streamEndpoint = conversations.stream(options.conversationId).url;

    // Initialize the underlying stream connection with event handlers
    const { data, isFetching, isStreaming, send, cancel } = useStream(streamEndpoint, {
        headers: {
            Accept: 'text/event-stream',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },

        // Process each SSE chunk as it arrives
        onData: onSSEChunk,
        // Handle stream completion
        onFinish: () => {
            if (options.onComplete) {
                options.onComplete({
                    content: contentBuffer.value,
                    reasoning: reasoningBuffer.value,
                    finishReason: finishReason.value,
                    usage: usage.value,
                });
            }
            reset();
        },
        // Handle stream errors
        onError: (e) => {
            console.error('Completion stream error:', e);
            error.value = e;
            if (options.onError) {
                options.onError(e);
            }
            cancel();
        },
    });

    /**
     * Sends a message to start a new completion stream.
     * Automatically resets state before sending.
     */
    const start = (message: Message) => {
        reset();
        send({
            ...message,
        });
    };

    // Return reactive state and control methods
    return shallowReactive<UseCompletionStreamReturn>({
        data,
        isFetching: isFetching,
        isStreaming: isStreaming,
        isReasoning: readonly(isReasoning),
        contentBuffer: readonly(contentBuffer),
        reasoningBuffer: readonly(reasoningBuffer),
        finishReason: readonly(finishReason),
        usage: readonly(usage),
        error: readonly(error),
        start,
        cancel,
        reset,
    });
}
