<!-- TODO: Remove leading "(free)" in model name and replace it by a stylized "free" badge -->

<script setup lang="ts">
    import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
    import { debouncedRef } from '@vueuse/core';
    import { Check, ChevronDown } from 'lucide-vue-next';
    import { computed, nextTick, ref, watch } from 'vue';

    interface ModelSelectorProps {
        modelValue: string;
        availableModels: Model[];
        nameAttr?: string;
    }

    const props = withDefaults(defineProps<ModelSelectorProps>(), {
        nameAttr: 'model',
    });

    const emit = defineEmits<{
        'update:modelValue': [value: string];
    }>();

    const isOpen = ref(false);
    const searchInput = ref('');
    const searchQuery = debouncedRef(searchInput, 300);
    const inputRef = ref<HTMLInputElement | null>(null);
    const listContainerRef = ref<HTMLDivElement | null>(null);
    const highlightedIndex = ref<number>(-1);
    const shouldScrollOnHighlight = ref(false);
    const isNavigatingWithKeyboard = ref(true);

    // This computed contains the "currently visible" models based on search query
    const filteredModels = computed(() => {
        if (!searchQuery.value) {
            return props.availableModels;
        }
        const query = searchQuery.value.toLowerCase();
        return props.availableModels.filter((model) => model.name.toLowerCase().includes(query));
    });

    // This method selects a model based on it's id
    const selectModel = (id: string) => {
        emit('update:modelValue', id);
        isOpen.value = false;
        searchInput.value = '';
        highlightedIndex.value = -1;
    };

    const highlightPreviousModel = () => {
        if (filteredModels.value.length === 0) return;

        if (highlightedIndex.value > 0) {
            highlightedIndex.value--;
        } else {
            highlightedIndex.value = filteredModels.value.length - 1;
        }
    }

    const highlightNextModel = () => {
        if (filteredModels.value.length === 0) return;

        if (highlightedIndex.value < filteredModels.value.length - 1) {
            highlightedIndex.value++;
        } else {
            highlightedIndex.value = 0;
        }
    }

    // This method select the currently highlighted model
    const selectHighlightedModel = () => {
        if (highlightedIndex.value >= 0 && highlightedIndex.value < filteredModels.value.length) {
            const model = filteredModels.value[highlightedIndex.value];
            selectModel(model.id);
        }
    };

    // This method handles mouse hover on a model item
    const handleModelHover = (index: number) => {
        if (isNavigatingWithKeyboard.value) return; // Ignore if navigating with keyboard
        shouldScrollOnHighlight.value = false; // Don't scroll on mouse hover
        highlightedIndex.value = index;
    };

    const handleDialogMouseMove = () => {
        isNavigatingWithKeyboard.value = false; // Reset keyboard navigation flag on mouse move
    };

    // This method handles key down events in the dialog
    const handleDialogKeyDown = (event: KeyboardEvent) => {
        // If user is typing in the search input, handle it differently
        const isInputFocused = document.activeElement === inputRef.value;

        // Handle arrow keys for navigation
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            isNavigatingWithKeyboard.value = true; // Mark as keyboard navigation
            shouldScrollOnHighlight.value = true; // Enable scroll for keyboard nav
            highlightNextModel();

            return;
        }

        if (event.key === 'ArrowUp') {
            event.preventDefault();
            isNavigatingWithKeyboard.value = true; // Mark as keyboard navigation
            shouldScrollOnHighlight.value = true; // Enable scroll for keyboard nav
            highlightPreviousModel();

            return;
        }

        // Handle Enter to select highlighted item
        if (event.key === 'Enter') {
            if (highlightedIndex.value >= 0) {
                event.preventDefault();
                selectHighlightedModel();
            }
            return;
        }

        // Auto-focus input when typing printable characters
        if (!isInputFocused && event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
            inputRef.value?.focus();
            // Let the event propagate naturally so the character is typed
        }
    };

    const getModelDisplay = (id: string) => {
        const model = props.availableModels.find((m: Model) => m.id === id);
        return model?.name || id;
    };

    const highlightButton = computed(() => {
        if (highlightedIndex.value >= 0) {
            return listContainerRef.value?.querySelector(`#model-option-${highlightedIndex.value}`);
        }
        return null;
    });

    // Initialize highlighted index when modal opens
    watch(isOpen, async (newValue) => {
        if (newValue) {
            await nextTick();

            // Find index of currently selected model
            const selectedIndex = filteredModels.value.findIndex((m) => m.id === props.modelValue);
            highlightedIndex.value = selectedIndex >= 0 ? selectedIndex : 0;

            // Scroll to selected model
            highlightButton.value?.scrollIntoView({
                block: 'center',
                behavior: 'instant',
            });

            // Focus the input
            inputRef.value?.focus();
        } else {
            // Reset state when closing
            searchInput.value = '';
            highlightedIndex.value = -1;
            isNavigatingWithKeyboard.value = true;
        }
    });

    // Handle search query changes - scroll to top and adjust highlighted index
    watch(searchQuery, async (newQuery, oldQuery) => {
        // Only reset if the query actually changed (not just debounce delay)
        if (newQuery === oldQuery) return;

        await nextTick();

        if (filteredModels.value.length === 0) {
            highlightedIndex.value = -1;
            return;
        }

        shouldScrollOnHighlight.value = false;
        highlightedIndex.value = 0;
        listContainerRef.value?.scroll({
            top: 0,
            behavior: 'smooth'
        })
    });

    // Scroll highlighted item into view (only for keyboard navigation)
    watch(
        highlightedIndex,
        async (newIndex) => {
            if (shouldScrollOnHighlight.value && newIndex >= 0) {
                await nextTick();
                highlightButton.value?.scrollIntoView({
                    block: 'center',
                    behavior: 'smooth',
                });
            }
        },
        { flush: 'post' },
    );

</script>

<template>
    <div>
        <input type="hidden" :name="props.nameAttr" :value="modelValue" />
        <button type="button" @click="isOpen = true"
            class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-secondary px-3 py-1.5 text-sm font-medium text-secondary-foreground transition-colors hover:bg-secondary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            aria-haspopup="dialog" :aria-expanded="isOpen">
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
                        <DialogPanel class="w-full max-w-xl overflow-hidden rounded-xl border bg-popover shadow-2xl"
                            @keydown="handleDialogKeyDown" @mousemove="handleDialogMouseMove">
                            <!-- Search Input -->
                            <div class="border-b p-4">
                                <input ref="inputRef" v-model="searchInput" type="text" placeholder="Search models..."
                                    class="w-full border-0 bg-transparent px-0 text-base text-foreground placeholder:text-muted-foreground focus:ring-0 focus:outline-none"
                                    role="combobox" aria-autocomplete="list" aria-controls="models-listbox"
                                    :aria-activedescendant="highlightedIndex >= 0 ? `model-option-${highlightedIndex}` : undefined"
                                    autofocus />
                            </div>

                            <span class="sr-only" role="status" aria-live="polite">{{ filteredModels.length }} models
                                available</span>

                            <!-- Models List -->
                            <div ref="listContainerRef" class="max-h-96 overflow-y-auto p-2" id="models-listbox"
                                role="listbox" aria-label="Available models">
                                <button v-for="(model, index) in filteredModels" :key="index"
                                    :id="`model-option-${index}`" @click="selectModel(model.id)" tabindex="-1"
                                    @mouseover="handleModelHover(index)" type="button" role="option"
                                    :aria-selected="modelValue === model.id" :class="[
                                        'flex w-full items-start gap-3 rounded-lg px-3 py-2.5 text-left transition-colors focus:outline-none cursor-pointer',
                                        highlightedIndex === index ? 'bg-accent' : '',
                                    ]">
                                    <Check
                                        :class="['mt-0.5 h-4 w-4 shrink-0', modelValue === model.id ? 'opacity-100' : 'opacity-0']" />
                                    <div class="flex-1 space-y-1">
                                        <div class="font-medium text-foreground">
                                            {{ model.name }}
                                        </div>
                                        <div v-if="model.description"
                                            class="text-sm text-muted-foreground line-clamp-2 text-ellipsis">
                                            {{ model.description }}
                                        </div>
                                    </div>
                                </button>
                                <div v-if="filteredModels.length === 0"
                                    class="px-3 py-8 text-center text-sm text-muted-foreground" role="status">
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
