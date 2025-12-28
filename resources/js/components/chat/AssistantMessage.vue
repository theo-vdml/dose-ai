<script lang="ts" setup>
    import MarkdownProse from './MarkdownProse.vue';

    const props = defineProps<{
        message: Message
    }>();

</script>

<template>
    <div>
        <div v-if="props.message.reasoning">
            <MarkdownProse size="sm" :content="props.message.reasoning" />
            <hr>
        </div>
        <MarkdownProse :content="props.message.content" />
    </div>
</template>

<style></style>
