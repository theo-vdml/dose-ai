/** * ScrollLockContainer Component * * A smart scroll container that automatically scrolls to the bottom when new content is added, * but respects user intent
by locking scroll position when the user manually scrolls up. * * This is particularly useful for chat interfaces, logs, or any streaming content where you want
* new content to auto-scroll unless the user is reviewing older content. */
<script lang="ts" setup>
import { useElementSize, useScroll } from '@vueuse/core';
import { onMounted, ref, useTemplateRef, watch } from 'vue';

const props = defineProps<{
    /** Whether to automatically scroll to bottom on component mount */
    startsAtBottom?: boolean;
    /** Distance from bottom (in pixels) to consider "arrived at bottom" */
    offsetBottom?: number;
    /** Whether to use smooth scrolling animation when auto-scrolling */
    smoothScroll?: boolean;
}>();

/**
 * Tracks whether scroll position is locked (user has scrolled up)
 * When locked, new content won't trigger auto-scroll
 */
const scrollLocked = ref(false);

/** Reference to the scrollable container element */
const container = useTemplateRef('container');

/** Reference to the content element inside the container */
const content = useTemplateRef('content');

/**
 * Track scroll state and direction using VueUse composable
 * - arrivedState.bottom: true when scrolled to bottom (within offsetBottom threshold)
 * - directions.top: true when user is actively scrolling upward
 */
const { arrivedState, directions } = useScroll(container, {
    offset: { bottom: props.offsetBottom || 0 },
});

/** Track content height changes to detect when new content is added */
const { height } = useElementSize(content);

/**
 * Scrolls the container to the bottom
 * Respects the smoothScroll prop for animation behavior
 */
const scrollToBottom = () => {
    if (!container.value) return;

    container.value?.scrollTo({
        top: container.value.scrollHeight,
        behavior: props.smoothScroll ? 'smooth' : 'auto',
    });
};

/**
 * Lock scroll when user actively scrolls upward
 * This prevents auto-scrolling from interrupting the user's review of older content
 */
watch(
    () => directions.top,
    (isScrollingTop) => {
        if (isScrollingTop) {
            scrollLocked.value = true;
        }
    },
);

/**
 * Unlock scroll when user scrolls back to the bottom
 * This re-enables auto-scrolling for new content since the user has returned
 * to the "live" view at the bottom
 */
watch(
    () => arrivedState.bottom,
    (hasArrivedBottom) => {
        if (hasArrivedBottom) {
            scrollLocked.value = false;
        }
    },
);

/**
 * Auto-scroll to bottom when new content is added (height increases)
 * Only triggers if:
 * 1. Scroll is not locked (user hasn't scrolled up)
 * 2. Height has actually increased (new content added)
 *
 * This is the core auto-scroll behavior for streaming/dynamic content
 */
watch(
    () => height.value,
    (newHeight, oldHeight) => {
        if (!scrollLocked.value && newHeight > oldHeight) {
            scrollToBottom();
        }
    },
);

/**
 * Initialize scroll position on component mount
 * If startsAtBottom is true, scroll to bottom immediately
 */
onMounted(() => {
    if (props.startsAtBottom) {
        scrollToBottom();
    }
});
</script>

<template>
    <!-- Scrollable container element -->
    <div ref="container">
        <!-- Content wrapper - height is tracked to detect content changes -->
        <div ref="content" class="h-fit w-full">
            <!-- Slot for dynamic content (e.g., chat messages, logs) -->
            <slot />
        </div>
    </div>
</template>

<style></style>
