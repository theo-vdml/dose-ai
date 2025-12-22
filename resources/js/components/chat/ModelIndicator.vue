<script lang="ts" setup>

    interface ModelIndicatorProps {
        label: string;
    }

    const props = defineProps<ModelIndicatorProps>();

</script>

<template>
    <div class="flex items-center gap-2 rounded-full bg-muted/50 px-3 py-1.5 text-sm font-medium text-foreground">
        <span class="relative flex size-2">
            <span class="relative inline-flex size-2 rounded-full bg-primary"></span>
        </span>
        <span>{{ label }}</span>
    </div>
</template>

<style></style>
