<script lang="ts" setup>
    import PromptForm from '@/components/PromptForm.vue';
    import AppLayout from '@/layouts/AppLayout.vue';
    import conversations from '@/routes/conversations';
    import { ModelsResponse } from '@/types/generated';
    import { Form, useForm } from '@inertiajs/vue3';

    const props = defineProps<{
        models: ModelsResponse;
        selectedModel?: string;
        message?: string;
        error?: string;
    }>()

    const form = useForm({
        model: props.selectedModel ?? '',
        message: props.message ?? '',
    })

    const submit = () => {
        form.post(conversations.store.url())
    }


</script>

<template>

    <AppLayout>

        <div class="flex h-full items-center justify-center p-4">
            <div class="w-full max-w-2xl space-y-8">
                <!-- Header -->
                <div class="space-y-2 text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-foreground">
                        Start a new Chat
                    </h1>
                    <p class="text-sm text-muted-foreground/80">
                        Start talking with the model of your choice
                    </p>
                </div>

                <!-- Prompt Form -->
                <Form @submit.prevent="submit">
                    <PromptForm :available-models="props.models" v-model:selectedModel="form.model"
                        v-model:message="form.message" :error="props.error" />
                    <p>{{ form.errors }}</p>
                </Form>

            </div>
        </div>

    </AppLayout>

</template>
