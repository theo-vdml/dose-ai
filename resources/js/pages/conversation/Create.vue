<script lang="ts" setup>
import { ModelSelector, PersonaSelector, PromptInput } from '@/components/chat';
import AppLayout from '@/layouts/AppLayout.vue';
import conversations from '@/routes/conversations';
import { useForm } from '@inertiajs/vue3';

interface Persona {
    id: string;
    name: string;
    description?: string;
}

const props = defineProps<{
    models: Model[];
    selectedModel?: string;
    message?: string;
    error?: string;
    personas: Persona[];
}>();

const form = useForm({
    model: props.selectedModel || (props.models.length > 0 ? props.models[0].id : ''),
    message: props.message || '',
    persona: null as string | null,
});

const submit = () => {
    form.post(conversations.store.url());
};
</script>

<template>
    <AppLayout>
        <div class="flex h-full items-center justify-center p-4">
            <div class="w-full max-w-2xl space-y-8">
                <!-- Header -->
                <div class="space-y-2 text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-foreground">Start a new Chat</h1>
                    <p class="text-sm text-muted-foreground/80">Start talking with the model of your choice</p>
                </div>

                <PromptInput v-model:message="form.message" @submit="submit">
                    <div class="flex items-center gap-2">
                        <PersonaSelector :available-personas="props.personas" v-model="form.persona" />
                        <ModelSelector :available-models="props.models ?? []" v-model="form.model" />
                    </div>
                </PromptInput>
            </div>
        </div>
    </AppLayout>
</template>
