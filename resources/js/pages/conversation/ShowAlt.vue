<script lang="ts" setup>
    import PromptForm from '@/components/PromptForm.vue';
    import { useFlash } from '@/composables/useFlash';
    import AppLayout from '@/layouts/AppLayout.vue';
    import { Models } from '@/types/generated';
    import { useForm } from '@inertiajs/vue3';
    import { useStream } from '@laravel/stream-vue';
    import { ref, computed } from 'vue';

    interface StreamChunk {
        type: 'content' | 'reasoning' | 'finish_reason';
        data: any;
    }

    const props = defineProps<{
        conversation: {
            id: number;
            title?: string;
            created_at: string;
            updated_at: string;
            model_id: string;
        };
        models: Models
    }>();

    const { flash } = useFlash();

    // Buffers
    const contentBuffer = ref('');
    const reasoningBuffer = ref('');
    const finishReason = ref<string | null>(null);

    const conversationId = props.conversation.id;

    const initialInput = flash.value.initialMessage ? {
        message: flash.value.initialMessage,
        model: props.conversation.model_id
    } : undefined;

    /**
     * Parse multiple SSE events from a single chunk
     */
    const parseSSEChunk = (rawChunk: string) => {
        // Split by double newline (SSE event separator)
        const events = rawChunk
            .split('\n\n')
            .filter(line => line.trim().length > 0);

        for (const event of events) {
            console.log('Parsing SSE event:', event);

            // Remove "data: " prefix
            const jsonString = event.replace(/^data:\s*/, '').trim();

            if (!jsonString) continue;

            if (jsonString === '[DONE]') {
                // Stream finished
                return;
            }

            try {
                const chunk: StreamChunk = JSON.parse(jsonString);

                switch (chunk.type) {
                    case 'content':
                        contentBuffer.value += chunk.data.delta;
                        break;

                    case 'reasoning':
                        reasoningBuffer.value += chunk.data.delta;
                        break;

                    case 'finish_reason':
                        finishReason.value = chunk.data.router + ' / ' + chunk.data.model;
                        break;
                }
            } catch (error) {
                console.error('Failed to parse SSE event:', error, jsonString);
            }
        }
    };

    const { data, isFetching, isStreaming, send, cancel } = useStream(
        `/conversations/${conversationId}/stream`,
        {
            initialInput,
            onData: parseSSEChunk, // ← Parse multiple events
        }
    );

    const isComplete = computed(() => finishReason.value !== null);

    const form = useForm({
        message: '',
        model: props.conversation.model_id,
    })

    const submit = () => {
        send({
            message: form.message.trim(),
            model: form.model.trim(),
        })
    }

    const message = ref('Explique moi la theorie des cordes');
    const model = ref('openai/gpt-4o-mini')
    const test = () => {
        send({
            message: message.value,
            model: model.value,
        })
    }
</script>

<template>
    <AppLayout>
        <div class="p-4">

            <button @click="test">test</button>

            <form @submit.prevent="submit" class="mb-4">
                <PromptForm v-model:message="form.message" v-model:model="form.model" :available-models="models" />
            </form>

            <h1 class="text-2xl font-bold mb-4">
                {{ conversation.title ? conversation.title : 'Conversation #' + conversation.id }}
            </h1>

            <div class="border p-4 rounded-md min-h-[200px]">

                <!-- Debug Info -->
                <div class="border-t pt-4 text-xs text-gray-500">
                    <p>Initial: {{ flash.initialMessage }}</p>
                    <p>Fetching: {{ isFetching }}</p>
                    <p>Streaming: {{ isStreaming }}</p>
                    <p>Finish Reason: {{ finishReason ?? 'streaming...' }}</p>
                    <p>Content Length: {{ contentBuffer.length }} chars</p>
                </div>

                <!-- Content Stream -->
                <div v-if="contentBuffer" class="mb-4">
                    <h3 class="font-semibold text-sm text-gray-600 mb-2">Response:</h3>
                    <p class="whitespace-pre-wrap font-mono text-sm">
                        {{ contentBuffer }}
                        <span v-if="isStreaming && !isComplete"
                            class="inline-block w-2 h-4 bg-blue-500 animate-pulse ml-1">▊</span>
                    </p>
                </div>

                <!-- Reasoning Stream (if model supports it) -->
                <div v-if="reasoningBuffer" class="mb-4 border-t pt-4">
                    <h3 class="font-semibold text-sm text-gray-600 mb-2">Reasoning:</h3>
                    <p class="whitespace-pre-wrap font-mono text-sm text-gray-700">
                        {{ reasoningBuffer }}
                    </p>
                </div>


                <div class="border-t pt-4 text-xs text-gray-500">
                    <pre>
{{ data }}
                    </pre>
                </div>

                <!-- Cancel Button -->
                <button v-if="isStreaming && !isComplete" @click="cancel"
                    class="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Stop Generation
                </button>
            </div>
        </div>
    </AppLayout>
</template>
