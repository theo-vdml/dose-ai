<script setup lang="ts">
    import PreferenceController from '@/actions/App/Http/Controllers/Settings/PreferenceController';
    import HeadingSmall from '@/components/HeadingSmall.vue';
    import InputError from '@/components/InputError.vue';
    import Textarea from '@/components/Textarea.vue';
    import { Button } from '@/components/ui/button';
    import { Label } from '@/components/ui/label';
    import AppLayout from '@/layouts/AppLayout.vue';
    import SettingsLayout from '@/layouts/settings/Layout.vue';
    import { edit } from '@/routes/preferences';
    import { type BreadcrumbItem } from '@/types';
    import { Form, Head } from '@inertiajs/vue3';

    interface Props {
        preferences: {
            instruction_prompt: string | null;
        };
    }

    const props = defineProps<Props>();

    const breadcrumbItems: BreadcrumbItem[] = [
        {
            title: 'Preferences',
            href: edit().url,
        },
    ];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">

        <Head title="Preferences" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Preferences" description="Customize your chat experience and default settings" />

                <Form v-bind="PreferenceController.update.form()" class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }">

                    <!-- Custom Instructions -->
                    <div class="grid gap-2">
                        <Label for="instruction_prompt">Custom instructions</Label>
                        <p class="text-sm text-muted-foreground">
                            Add custom instructions that will be included in all
                            your conversations. This helps the AI understand
                            your preferences and context better.
                        </p>
                        <Textarea id="instruction_prompt" name="instruction_prompt"
                            :model-value="props.preferences.instruction_prompt ?? ''"
                            placeholder="e.g., Always use TypeScript, prefer functional programming, explain complex concepts simply..."
                            :rows="5" :max-lines="15" class="border-2 border-input p-2 rounded-lg" />
                        <p class="text-xs text-muted-foreground">
                            Maximum 5000 characters
                        </p>
                        <InputError class="mt-2" :message="errors.instruction_prompt" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button class="cursor-pointer" :disabled="processing" data-test="update-preferences-button">Save
                            preferences</Button>

                        <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                            <p v-show="recentlySuccessful" class="text-sm text-neutral-600">
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
