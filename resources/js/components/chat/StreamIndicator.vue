<script lang="ts" setup>

    interface StreamIndicatorProps {
        isFetching?: boolean;
        isStreaming?: boolean;
    }

    defineProps<StreamIndicatorProps>();

</script>

<template>
    <div v-if="isFetching" class="flex items-center gap-2 text-sm text-muted-foreground">
        <span class="relative flex size-2"> <span
                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
            <span class="relative inline-flex size-2 rounded-full bg-orange-500"></span></span>
        <span>Fetching ...</span>
    </div>
    <div v-if="isStreaming" class="flex items-center gap-2 text-sm text-muted-foreground">
        <span class="relative flex size-2"> <span
                class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex size-2 rounded-full bg-emerald-500"></span></span>
        <span>Streaming ...</span>
    </div>
</template>

<style></style>
