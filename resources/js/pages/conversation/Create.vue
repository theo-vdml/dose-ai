<script lang="ts" setup>
    import { ModelSelector, PromptInput } from '@/components/chat';
    import AppLayout from '@/layouts/AppLayout.vue';
    import conversations from '@/routes/conversations';
    import { Model, Models } from '@/types/generated';
    import { useForm } from '@inertiajs/vue3';

    const props = defineProps<{
        models: Model[];
        selectedModel?: string;
        message?: string;
        error?: string;
    }>()

    console.log(props);

    const form = useForm({
        model: props.selectedModel || (props.models.length > 0 ? props.models[0].id : ''),
        message: props.message || '',
    });

    const submit = () => {
        form.post(conversations.store.url());
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

                <PromptInput v-model:message="form.message" @submit="submit">
                    <ModelSelector :available-models="props.models ?? []" v-model="form.model" />
                </PromptInput>

            </div>
        </div>

    </AppLayout>

</template>
