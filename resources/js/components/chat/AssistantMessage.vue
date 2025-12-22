<script lang="ts" setup>
    import MarkdownProse from './MarkdownProse.vue';

    const props = defineProps<{
        message: Message
    }>();

</script>

<template>
    <div>
        <MarkdownProse :content="props.message.content" />
    </div>
</template>

<style></style>
