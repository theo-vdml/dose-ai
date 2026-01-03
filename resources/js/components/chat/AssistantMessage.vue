<script lang="ts" setup>
    import { computed } from 'vue';
    import MarkdownProse from './MarkdownProse.vue';

    const props = defineProps<{
        message: Message
    }>();

    const shouldDisplay = computed(() => {
        return !!(props.message.content || props.message.reasoning);
    });

</script>

<template>
    <div v-if="shouldDisplay">
        <div v-if="props.message.reasoning">
            <MarkdownProse size="sm" :content="props.message.reasoning" />
            <hr>
        </div>
        <MarkdownProse :content="props.message.content" />
    </div>
</template>

<style></style>
