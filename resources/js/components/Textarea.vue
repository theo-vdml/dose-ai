<script setup lang="ts">
/**
 * Auto-resizing textarea component with configurable maximum height.
 *
 * Features:
 * - Automatically expands vertically to fit content
 * - Limits expansion to a maximum number of lines
 * - Supports v-model binding for reactive updates
 */

import { ref, watch, onMounted } from 'vue';
import { useVModel } from '@vueuse/core';

// ============================================================================
// Types & Interfaces
// ============================================================================

/**
 * Component props definition
 */
interface TextareaProps {
    /** Two-way bound value of the textarea */
    modelValue?: string;
    /** Placeholder text when textarea is empty */
    placeholder?: string;
    /** HTML name attribute for form submission */
    name?: string;
    /** HTML id attribute for labeling */
    id?: string;
    /** Maximum number of lines before scrolling (default: 10) */
    maxLines?: number;
    /** Additional CSS classes to apply */
    class?: string;
}

/**
 * Component emits definition
 */
interface TextareaEmits {
    'update:modelValue': [value: string];
}

// ============================================================================
// Component Setup
// ============================================================================

const props = withDefaults(defineProps<TextareaProps>(), {
    modelValue: '',
    placeholder: '',
    maxLines: 10,
    class: '',
});

const emit = defineEmits<TextareaEmits>();

// ============================================================================
// Reactive State
// ============================================================================

/** Two-way bound model value using VueUse composable */
const modelValue = useVModel(props, 'modelValue', emit);

/** Reference to the textarea DOM element */
const textareaRef = ref<HTMLTextAreaElement | null>(null);

// ============================================================================
// Methods
// ============================================================================

/**
 * Automatically resizes the textarea based on content height.
 * Respects the maxLines constraint to prevent unlimited expansion.
 */
const autoResize = () => {
    const textarea = textareaRef.value;
    if (!textarea) return;

    // Reset height to recalculate scroll height
    textarea.style.height = 'auto';

    // Calculate maximum allowed height based on line height and maxLines
    const lineHeight = parseInt(getComputedStyle(textarea).lineHeight);
    const maxHeight = lineHeight * props.maxLines;

    // Set height to content size, capped at maximum
    const newHeight = Math.min(textarea.scrollHeight, maxHeight);
    textarea.style.height = `${newHeight}px`;
};

// ============================================================================
// Lifecycle Hooks
// ============================================================================

/** Initialize textarea height on mount */
onMounted(() => {
    autoResize();
});

/** Watch for content changes and resize accordingly */
watch(modelValue, () => {
    autoResize();
});
</script>

<template>
    <textarea
        ref="textareaRef"
        v-model="modelValue"
        :id="id"
        :name="name"
        :placeholder="placeholder"
        :class="class"
        rows="1"
        class="block w-full resize-none border-0 bg-transparent text-base focus:ring-0 focus:outline-none"
    />
</template>
