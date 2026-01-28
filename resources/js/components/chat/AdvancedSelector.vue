<script setup lang="ts" generic="T extends { id: string; name: string; description?: string; icon?: any }">
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { debouncedRef } from '@vueuse/core';
import { Check, ChevronDown } from 'lucide-vue-next';
import { computed, nextTick, ref, useId, watch } from 'vue';

interface Props {
    modelValue: string | null;
    options: T[];
    label?: string; // Button text when nothing is selected
    nameAttr?: string; // For the hidden input

    // Mode configuration
    searchable?: boolean;

    // Text Props
    title?: string; // Header title (used if not searchable)
    description?: string; // Header description (used if not searchable)
    searchPlaceholder?: string; // Input placeholder
    emptyMessage?: string;
}

const props = withDefaults(defineProps<Props>(), {
    nameAttr: 'selection',
    searchable: false,
    label: 'Select option',
    searchPlaceholder: 'Search...',
    emptyMessage: 'No options found.',
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

// Generate unique id for aria
const uniqueId = useId();
const listboxId = `${uniqueId}-listbox`;
const optionIdPrefix = `${uniqueId}-opt-`;
const descIdPrefix = `${uniqueId}-desc-`;

const isOpen = ref(false);

// Search Logic
const searchInput = ref('');
const searchQuery = debouncedRef(searchInput, 300);
const inputRef = ref<HTMLInputElement | null>(null);
const liveRegionText = ref('');

// Navigation State
const listContainerRef = ref<HTMLDivElement | null>(null);
const highlightedIndex = ref<number>(-1);
const shouldScrollOnHighlight = ref(false);
const isNavigatingWithKeyboard = ref(true);

// Filtered Options
const filteredOptions = computed(() => {
    if (!props.searchable || !searchQuery.value) {
        return props.options;
    }
    const query = searchQuery.value.toLowerCase();
    return props.options.filter((option) => option.name.toLowerCase().includes(query));
});

// Selection Logic
const selectOption = (id: string) => {
    emit('update:modelValue', id);
    isOpen.value = false;
    searchInput.value = '';
    highlightedIndex.value = -1;
};

// Display Logic (Button Text)
const selectedOptionDisplay = computed(() => {
    if (!props.modelValue) return props.label;
    const option = props.options.find((o) => o.id === props.modelValue);
    return option?.name || props.modelValue;
});

const selectedOptionIcon = computed(() => {
    if (!props.modelValue) return null;
    const option = props.options.find((o) => o.id === props.modelValue);
    return option?.icon || null;
});

// Navigation Logic
const highlightPrevious = () => {
    if (filteredOptions.value.length === 0) return;
    highlightedIndex.value = highlightedIndex.value > 0 ? highlightedIndex.value - 1 : filteredOptions.value.length - 1;
};

const highlightNext = () => {
    if (filteredOptions.value.length === 0) return;
    highlightedIndex.value = highlightedIndex.value < filteredOptions.value.length - 1 ? highlightedIndex.value + 1 : 0;
};

const highlightFirst = () => {
    if (filteredOptions.value.length === 0) return;
    highlightedIndex.value = 0;
};

const highlightLast = () => {
    if (filteredOptions.value.length === 0) return;
    highlightedIndex.value = filteredOptions.value.length - 1;
};

const selectHighlighted = () => {
    if (highlightedIndex.value >= 0 && highlightedIndex.value < filteredOptions.value.length) {
        selectOption(filteredOptions.value[highlightedIndex.value].id);
    }
};

// Event Handlers
const handleHover = (index: number) => {
    if (isNavigatingWithKeyboard.value) return;
    shouldScrollOnHighlight.value = false;
    highlightedIndex.value = index;
};

const handleMouseMove = () => {
    isNavigatingWithKeyboard.value = false;
};

const handleKeyDown = (event: KeyboardEvent) => {
    const isInputFocused = document.activeElement === inputRef.value;

    if (event.key === 'ArrowDown') {
        event.preventDefault();
        isNavigatingWithKeyboard.value = true;
        shouldScrollOnHighlight.value = true;
        highlightNext();
        return;
    }

    if (event.key === 'ArrowUp') {
        event.preventDefault();
        isNavigatingWithKeyboard.value = true;
        shouldScrollOnHighlight.value = true;
        highlightPrevious();
        return;
    }

    if (event.key === 'Home') {
        event.preventDefault();
        isNavigatingWithKeyboard.value = true;
        shouldScrollOnHighlight.value = true;
        highlightFirst();
        return;
    }

    if (event.key === 'End') {
        event.preventDefault();
        isNavigatingWithKeyboard.value = true;
        shouldScrollOnHighlight.value = true;
        highlightLast();
        return;
    }

    if (event.key === 'Enter') {
        if (highlightedIndex.value >= 0) {
            event.preventDefault();
            selectHighlighted();
        }
        return;
    }

    // Autofocus search input if searchable and user starts typing
    if (props.searchable && !isInputFocused && event.key.length === 1 && !event.ctrlKey && !event.metaKey && !event.altKey) {
        inputRef.value?.focus();
    }
};

// Scroll Management
const highlightButton = computed(() => {
    if (highlightedIndex.value >= 0) {
        return listContainerRef.value?.querySelector(`#${optionIdPrefix}${highlightedIndex.value}`);
    }
    return null;
});

// Watchers
watch(isOpen, async (newValue) => {
    if (newValue) {
        await nextTick();
        const index = filteredOptions.value.findIndex((o) => o.id === props.modelValue);
        highlightedIndex.value = index >= 0 ? index : 0;

        highlightButton.value?.scrollIntoView({ block: 'center', behavior: 'instant' });

        if (props.searchable) {
            inputRef.value?.focus();
        }
    } else {
        searchInput.value = '';
        highlightedIndex.value = -1;
        isNavigatingWithKeyboard.value = true;
    }
});

watch(searchQuery, async (newQuery, oldQuery) => {
    if (newQuery === oldQuery) return;
    await nextTick();

    const count = filteredOptions.value.length;
    if (count > 0) {
        liveRegionText.value = `${count} option${count > 1 ? 's' : ''} found.`;
        shouldScrollOnHighlight.value = false;
        highlightedIndex.value = 0;
        listContainerRef.value?.scroll({ top: 0, behavior: 'smooth' });
    } else {
        liveRegionText.value = 'No results found';
        highlightedIndex.value = -1;
    }
});

watch(
    highlightedIndex,
    async (newIndex) => {
        if (shouldScrollOnHighlight.value && newIndex >= 0) {
            await nextTick();
            highlightButton.value?.scrollIntoView({ block: 'center', behavior: 'smooth' });
        }
    },
    { flush: 'post' },
);
</script>

<template>
    <div>
        <input type="hidden" :name="nameAttr" :value="modelValue || ''" />

        <span class="sr-only" role="status" aria-live="polite" aria-atomic="true">
            {{ liveRegionText }}
        </span>

        <button
            type="button"
            @click="isOpen = true"
            class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-secondary px-3 py-1.5 text-sm font-medium text-secondary-foreground transition-colors hover:bg-secondary/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
            aria-haspopup="listbox"
            :aria-expanded="isOpen"
            :aria-label="label"
            :aria-controls="listboxId"
        >
            <component v-if="selectedOptionIcon" :is="selectedOptionIcon" class="h-3.5 w-3.5" aria-hidden="true" />
            <span>{{ selectedOptionDisplay }}</span>
            <ChevronDown class="h-3.5 w-3.5 opacity-50" aria-hidden="true" />
        </button>

        <TransitionRoot :show="isOpen" as="template">
            <Dialog @close="isOpen = false" class="relative z-50" :initialFocus="searchable ? inputRef : listContainerRef">
                <TransitionChild
                    as="template"
                    enter="ease-out duration-200 motion-reduce:duration-0"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in duration-150 motion-reduce:duration-0"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" />
                </TransitionChild>

                <div class="fixed inset-0 flex items-center justify-center p-4">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-200"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="ease-in duration-150"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full max-w-xl overflow-hidden rounded-xl border bg-popover shadow-2xl"
                            @keydown="handleKeyDown"
                            @mousemove="handleMouseMove"
                        >
                            <div class="border-b p-4">
                                <div v-if="searchable">
                                    <input
                                        ref="inputRef"
                                        v-model="searchInput"
                                        type="text"
                                        :placeholder="searchPlaceholder"
                                        class="w-full border-0 bg-transparent px-0 text-base text-foreground placeholder:text-muted-foreground focus:ring-0 focus:outline-none"
                                        role="combobox"
                                        aria-autocomplete="list"
                                        aria-expanded="true"
                                        :aria-controls="listboxId"
                                        :aria-label="searchPlaceholder"
                                        :aria-activedescendant="highlightedIndex >= 0 ? `${optionIdPrefix}${highlightedIndex}` : undefined"
                                    />
                                </div>
                                <div v-else>
                                    <h3 class="text-lg font-semibold text-foreground">{{ title }}</h3>
                                    <p v-if="description" class="mt-1 text-sm text-muted-foreground">{{ description }}</p>
                                </div>
                            </div>

                            <ul
                                ref="listContainerRef"
                                :id="listboxId"
                                class="max-h-96 overflow-y-auto p-2 outline-none"
                                :tabindex="searchable ? -1 : 0"
                                role="listbox"
                            >
                                <li
                                    v-for="(option, index) in filteredOptions"
                                    :key="option.id"
                                    :id="`${optionIdPrefix}${index}`"
                                    @click="selectOption(option.id)"
                                    @mouseover="handleHover(index)"
                                    role="option"
                                    :aria-selected="modelValue === option.id"
                                    :aria-posinset="index + 1"
                                    :aria-setsize="filteredOptions.length"
                                    :aria-describedby="option.description ? `${descIdPrefix}${index}` : undefined"
                                    :class="[
                                        'flex min-h-11 w-full cursor-pointer items-start gap-3 rounded-lg px-3 py-2.5 text-left transition-colors focus:outline-none',
                                        highlightedIndex === index ? 'bg-accent' : '',
                                    ]"
                                >
                                    <Check :class="['mt-0.5 h-4 w-4 shrink-0', modelValue === option.id ? 'opacity-100' : 'opacity-0']" aria-hidden="true" />

                                    <span v-if="option.icon" class="block rounded-md border border-neutral-700 bg-neutral-900 p-1" aria-hidden="true">
                                        <component :is="option.icon" class="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground" />
                                    </span>

                                    <span class="flex-1 space-y-1">
                                        <span class="block font-medium text-foreground">
                                            {{ option.name }}
                                        </span>
                                        <span
                                            v-if="option.description"
                                            :id="`${descIdPrefix}${index}`"
                                            class="line-clamp-2 block text-sm text-ellipsis text-muted-foreground"
                                        >
                                            {{ option.description }}
                                        </span>
                                    </span>
                                </li>

                                <li
                                    v-if="filteredOptions.length === 0"
                                    class="px-3 py-8 text-center text-sm text-muted-foreground"
                                    role="option"
                                    aria-disabled="true"
                                >
                                    {{ emptyMessage }}
                                </li>
                            </ul>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>
