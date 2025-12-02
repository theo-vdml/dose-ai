<script setup lang="ts">
import { completion } from '@/actions/App/Http/Controllers/QuickPromptController';
import AppLayout from '@/layouts/AppLayout.vue';
import { ModelsResponse } from '@/types/generated';
import { useForm } from '@inertiajs/vue3';

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
    form.post(completion().url);
};
</script>

<template>
    <AppLayout> </AppLayout>
</template>
