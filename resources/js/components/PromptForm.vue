<script setup lang="ts">
    import { ModelsResponse } from '@/types/generated';
    import { useVModel } from '@vueuse/core';
    import { ArrowUp } from 'lucide-vue-next';
    import ModelSelector from './ModelSelector.vue';
    import Textarea from './Textarea.vue';

    type PromptFormOptions = {
        placeholder?: string;
        fallbackModel?: string;
        messageInputName?: string;
        modelInputName?: string;
    };

    const props = withDefaults(
        defineProps<{
            availableModels: ModelsResponse;
            selectedModel: string;
            message?: string;
            error?: string;
            options?: PromptFormOptions;
            class?: string;
        }>(),
        {
            class: '',
        },
    );

    const emit = defineEmits<{
        'update:selectedModel': [value: string];
        'update:message': [value: string];
    }>();

    const selectedModelValue = useVModel(props, 'selectedModel', emit);
    const messageValue = useVModel(props, 'message', emit);

    const options = {
        placeholder: 'Enter your prompt...',
        fallbackModel: props.availableModels.data[0]?.id || '',
        messageInputName: 'message',
        modelInputName: 'model',
        ...props.options,
    };
</script>

<template>
    <div :class="props.class">
        <!-- Main Input Container -->
        <div class="relative rounded-3xl border bg-card shadow-sm transition-shadow hover:shadow-md">
            <!-- Textarea -->
            <Textarea id="message-input" :name="options.messageInputName" v-model="messageValue"
                :placeholder="options.placeholder" :max-lines="10"
                class="px-6 py-5 text-foreground placeholder:text-muted-foreground" />

            <!-- Bottom Toolbar -->
            <div class="flex items-center gap-3 px-4 pb-4">
                <!-- Model Selector -->
                <ModelSelector v-model="selectedModelValue" :available-models="availableModels"
                    :name="options.modelInputName" />

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Submit Button -->
                <button type="submit"
                    class="flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-full bg-primary text-primary-foreground transition-colors hover:bg-primary/90 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50">
                    <ArrowUp class="h-4 w-4" />
                </button>
            </div>
        </div>

        <!-- Error Message -->
        <div v-if="error"
            class="mt-3 rounded-lg border border-destructive/50 bg-destructive/10 px-4 py-3 text-sm text-destructive">
            {{ error }}
        </div>
    </div>
</template>
