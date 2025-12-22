<script lang="ts" setup>
    import { computed } from 'vue';


    const props = defineProps<{
        message: Message
    }>();

    const dateFormatted = computed(() => {
        return new Date(props.message.created_at).toLocaleString();
    });

</script>

<template>
    <div class="flex flex-col items-end gap-2">
        <div class="max-w-2xl w-fit py-4 px-6 bg-secondary rounded-2xl rounded-br-none">
            {{ props.message.content }}
        </div>
        <span class="text-xs text-muted-foreground ml-auto">
            You · {{ dateFormatted }}
        </span>
    </div>
</template>

<style></style>
