<script lang="ts" setup>
    import AssistantMessage from './AssistantMessage.vue';
    import UserMessage from './UserMessage.vue';

    const props = defineProps<{
        messages: Message[],
        waiting?: boolean,
    }>();

</script>

<template>
    <div class="mx-auto max-w-5xl p-8 space-y-8">
        <template v-for="msg in props.messages" :key="msg.id">
            <AssistantMessage v-if="msg.role === 'assistant'" :message="msg" />
            <UserMessage v-else-if="msg.role === 'user'" :message="msg" />
        </template>
        <template v-if="props.waiting">
            <div class="flex gap-2">
                <div class="size-2 bg-white rounded-full animate-bounce delay-0"></div>
                <div class="size-2 bg-white rounded-full animate-bounce delay-150"></div>
                <div class="size-2 bg-white rounded-full animate-bounce delay-300"></div>
            </div>
        </template>
    </div>
</template>

<style></style>
