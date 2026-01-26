<script lang="ts" setup>
import { computed } from 'vue';

const props = defineProps<{
    message: Message;
}>();

const dateFormatted = computed(() => {
    return new Date(props.message.created_at).toLocaleString();
});
</script>

<template>
    <div class="flex flex-col items-end gap-2">
        <div class="w-fit max-w-2xl rounded-2xl rounded-br-none bg-secondary px-6 py-4">
            {{ props.message.content }}
        </div>
        <span class="ml-auto text-xs text-muted-foreground"> You · {{ dateFormatted }} </span>
    </div>
</template>

<style></style>
