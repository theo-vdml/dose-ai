<script setup lang="ts">
import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.css'; // ou un autre thème
import MarkdownIt from 'markdown-it';
import { computed, useSlots } from 'vue';

interface MarkdownProseProps {
    content?: string;
}

const props = defineProps<MarkdownProseProps>();

const slots = useSlots();

const content = computed(() => {
    // Prioritize slot content over prop content
    if (slots.default) {
        return slots.default()[0]?.children as string;
    }
    return props.content ?? '';
});

const md: MarkdownIt = new MarkdownIt({
    html: true,
    linkify: true,
    typographer: true,
    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return hljs.highlight(str, { language: lang }).value;
            } catch (__) {}
        }
        return ''; // use external default escaping
    },
});

const renderedMarkdown = computed(() => {
    return md.render(content.value ?? '');
});
</script>

<template>
    <div
        class="prose max-w-none dark:prose-invert"
        v-html="renderedMarkdown"
    ></div>
</template>
