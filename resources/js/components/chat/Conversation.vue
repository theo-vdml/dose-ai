<script lang="ts" setup>
import AssistantMessage from './AssistantMessage.vue';
import UserMessage from './UserMessage.vue';

const props = defineProps<{
    messages: Message[];
}>();
</script>

<template>
    <div class="mx-auto max-w-5xl space-y-8 p-8">
        <template v-for="msg in props.messages" :key="msg.id">
            <AssistantMessage v-if="msg.role === 'assistant'" :message="msg" />
            <UserMessage v-else-if="msg.role === 'user'" :message="msg" />
        </template>
    </div>
</template>

<style></style>
