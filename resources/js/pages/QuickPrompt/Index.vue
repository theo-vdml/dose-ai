<script setup lang="ts">
import { completion } from '@/actions/App/Http/Controllers/QuickPromptController';
import MarkdownProse from '@/components/MarkdownProse.vue';
import PromptForm from '@/components/PromptForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { ModelsResponse } from '@/types/generated';
import { Form, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    models: ModelsResponse;
    selectedModel: string;
    message?: string;
    response?: string;
    error?: string;
}>();

const form = useForm({
    model: props.selectedModel,
    message: props.message ?? '',
});

const submit = () => {
    console.log('Debug mode: Submitting form with data:', form.data());
    form.post(completion().url);
};
</script>

<template>
    <AppLayout>
        <template v-if="response">
            <div class="mx-auto max-w-5xl space-y-8 p-8">
                <!-- Header with Actions -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-semibold text-foreground">
                            QuickPrompt Result
                        </h2>
                        <div class="flex gap-3">
                            <a :href="`/quick-prompt?model=${form.model}&message=${encodeURIComponent(props.message ?? '')}`"
                                class="rounded-md border border-border bg-background px-4 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent">
                                Edit Prompt
                            </a>
                            <a href="/quick-prompt"
                                class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90">
                                New Prompt
                            </a>
                        </div>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        {{ props.message }}
                    </p>
                </div>

                <!-- AI Response -->
                <div class="border-t border-border pt-8">
                    <MarkdownProse>
                        {{ response }}
                    </MarkdownProse>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="flex h-full items-center justify-center p-4">
                <div class="w-full max-w-2xl space-y-8">
                    <!-- Header -->
                    <div class="space-y-2 text-center">
                        <h1 class="text-4xl font-bold tracking-tight text-foreground">
                            QuickPrompt
                        </h1>
                        <p class="text-sm text-muted-foreground/80">
                            Get a single answer to your question—no
                            conversation, just the response you need.
                        </p>
                    </div>

                    <!-- Prompt Form -->
                    <Form @submit.prevent="submit">
                        <PromptForm :available-models="props.models" v-model:selectedModel="form.model"
                            v-model:message="form.message" :error="props.error" />
                    </Form>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
