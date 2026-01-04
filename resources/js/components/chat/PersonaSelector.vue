<script setup lang="ts">
    import {
        Dialog,
        DialogPanel,
        TransitionChild,
        TransitionRoot,
    } from '@headlessui/vue';
    import { Check, ChevronDown } from 'lucide-vue-next';
    import { computed, nextTick, ref, watch } from 'vue';

    interface Persona {
        id: string;
        name: string;
        description?: string;
    }

    interface PersonaSelectorProps {
        modelValue: string | null;
        availablePersonas: Persona[];
        nameAttr?: string;
    }

    const props = withDefaults(defineProps<PersonaSelectorProps>(), {
        nameAttr: 'persona_id',
    });

    const emit = defineEmits<{
        'update:modelValue': [value: string | null];
    }>();

    const isOpen = ref(false);
    const selectedButtonRef = ref<HTMLButtonElement | null>(null);

    const selectPersona = (id: string | null) => {
        emit('update:modelValue', id);
        isOpen.value = false;
    };

    const getPersonaDisplay = (id: string | null) => {
        if (!id) {
            return 'No persona';
        }
        const persona = props.availablePersonas.find(
            (s: Persona) => s.id === id,
        );
        return persona?.name || id;
    };

    // Scroll to selected scenario when modal opens
    watch(isOpen, async (newValue) => {
        if (newValue) {
            await nextTick();
            selectedButtonRef.value?.scrollIntoView({
                block: 'center',
                behavior: 'instant',
            });
        }
    });
</script>

<template>
    <div>
        <input type="hidden" :name="props.nameAttr" :value="modelValue || ''" />
        <button type="button" @click="isOpen = true"
            class="inline-flex items-center gap-2 rounded-lg bg-secondary px-3 py-1.5 text-sm font-medium text-secondary-foreground transition-colors hover:bg-secondary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 cursor-pointer">
            <span>{{ getPersonaDisplay(modelValue) }}</span>
            <ChevronDown class="h-3.5 w-3.5 opacity-50" />
        </button>

        <!-- Scenario Selection Modal -->
        <TransitionRoot :show="isOpen" as="template">
            <Dialog @close="isOpen = false" class="relative z-50">
                <!-- Backdrop -->
                <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" />
                </TransitionChild>

                <!-- Modal Container -->
                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100" leave="ease-in duration-150" leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel class="w-full max-w-xl overflow-hidden rounded-xl border bg-popover shadow-2xl">
                            <!-- Header -->
                            <div class="border-b p-4">
                                <h3 class="text-lg font-semibold text-foreground">Select a Persona</h3>
                                <p class="text-sm text-muted-foreground mt-1">
                                    Choose an assistant Persona or start without one
                                </p>
                            </div>

                            <!-- Personas List -->
                            <div class="max-h-96 overflow-y-auto p-2">
                                <!-- No Persona Option -->
                                <button :ref="(el) => {
                                    if (modelValue === null) {
                                        selectedButtonRef = el as HTMLButtonElement;
                                    }
                                }" @click="selectPersona(null)" type="button"
                                    class="flex w-full items-start gap-3 rounded-lg px-3 py-2.5 text-left transition-colors hover:bg-accent focus:bg-accent focus:outline-none">
                                    <Check :class="[
                                        'mt-0.5 h-4 w-4 shrink-0',
                                        modelValue === null
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    ]" />
                                    <div class="flex-1 space-y-1">
                                        <div class="font-medium text-foreground">
                                            No Persona
                                        </div>
                                        <div class="text-sm text-muted-foreground">
                                            Start a conversation without any predefined context
                                        </div>
                                    </div>
                                </button>

                                <!-- Available Personas -->
                                <button v-for="persona in availablePersonas" :key="persona.id" :ref="(el) => {
                                    if (modelValue === persona.id) {
                                        selectedButtonRef = el as HTMLButtonElement;
                                    }
                                }" @click="selectPersona(persona.id)" type="button"
                                    class="flex w-full items-start gap-3 rounded-lg px-3 py-2.5 text-left transition-colors hover:bg-accent focus:bg-accent focus:outline-none">
                                    <Check :class="[
                                        'mt-0.5 h-4 w-4 shrink-0',
                                        modelValue === persona.id
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    ]" />
                                    <div class="flex-1 space-y-1">
                                        <div class="font-medium text-foreground">
                                            {{ persona.name }}
                                        </div>
                                        <div v-if="persona.description" class="text-sm text-muted-foreground">
                                            {{ persona.description }}
                                        </div>
                                    </div>
                                </button>

                                <div v-if="availablePersonas.length === 0"
                                    class="px-3 py-8 text-center text-sm text-muted-foreground">
                                    No personas available.
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
