<!-- TODO: Add keyboard navigation -->
<!-- TODO: Scroll to first result on search query changes -->
<!-- TODO: Remove leading "(free)" in model name and replace it by a stylized "free" badge -->

<script setup lang="ts">
    import { Model, ModelsResponse } from '@/types/generated';
    import {
        Dialog,
        DialogPanel,
        TransitionChild,
        TransitionRoot,
    } from '@headlessui/vue';
    import { debouncedRef } from '@vueuse/core';
    import { Check, ChevronDown } from 'lucide-vue-next';
    import { computed, nextTick, ref, watch } from 'vue';

    interface ModelSelectorProps {
        modelValue: string;
        availableModels: ModelsResponse;
        name?: string;
        class?: string;
    }

    const props = withDefaults(defineProps<ModelSelectorProps>(), {
        name: 'model',
        class: '',
    });

    const emit = defineEmits<{
        'update:modelValue': [value: string];
    }>();

    const isOpen = ref(false);
    const searchInput = ref('');
    const searchQuery = debouncedRef(searchInput, 300);
    const selectedButtonRef = ref<HTMLButtonElement | null>(null);

    const filteredModels = computed(() => {
        if (!searchQuery.value) {
            return props.availableModels.data;
        }
        const query = searchQuery.value.toLowerCase();
        return props.availableModels.data.filter((model: Model) =>
            model.name.toLowerCase().includes(query),
        );
    });

    const selectModel = (modelId: string) => {
        emit('update:modelValue', modelId);
        isOpen.value = false;
        searchInput.value = '';
    };

    const getModelDisplay = (modelId: string) => {
        const model = props.availableModels.data.find(
            (m: Model) => m.id === modelId,
        );
        return model?.name || modelId;
    };

    // Scroll to selected model when modal opens
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
        <input type="hidden" :name="name" :value="modelValue" />
        <button type="button" @click="isOpen = true" :class="class"
            class="inline-flex items-center gap-2 rounded-lg bg-secondary px-3 py-1.5 text-sm font-medium text-secondary-foreground transition-colors hover:bg-secondary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
            <span>{{ getModelDisplay(modelValue) }}</span>
            <ChevronDown class="h-3.5 w-3.5 opacity-50" />
        </button>

        <!-- Model Selection Modal -->
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
                            <!-- Search Input -->
                            <div class="border-b p-4">
                                <input v-model="searchInput" type="text" placeholder="Search models..."
                                    class="w-full border-0 bg-transparent px-0 text-base text-foreground placeholder:text-muted-foreground focus:ring-0 focus:outline-none"
                                    autofocus />
                            </div>

                            <!-- Models List -->
                            <div class="max-h-96 overflow-y-auto p-2">
                                <button v-for="model in filteredModels" :key="model.id" :ref="(el) => {
                                    if (modelValue === model.id) {
                                        selectedButtonRef =
                                            el as HTMLButtonElement;
                                    }
                                }
                                    " @click="selectModel(model.id)" type="button"
                                    class="flex w-full items-start gap-3 rounded-lg px-3 py-2.5 text-left transition-colors hover:bg-accent focus:bg-accent focus:outline-none">
                                    <Check :class="[
                                        'mt-0.5 h-4 w-4 shrink-0',
                                        modelValue === model.id
                                            ? 'opacity-100'
                                            : 'opacity-0',
                                    ]" />
                                    <div class="flex-1 space-y-1">
                                        <div class="font-medium text-foreground">
                                            {{ model.name }}
                                        </div>
                                        <div v-if="model.description" class="text-sm text-muted-foreground">
                                            {{ model.description }}
                                        </div>
                                    </div>
                                </button>
                                <div v-if="filteredModels.length === 0"
                                    class="px-3 py-8 text-center text-sm text-muted-foreground">
                                    No models found.
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
