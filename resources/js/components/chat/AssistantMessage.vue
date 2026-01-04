<script lang="ts" setup>
    import { computed, ref, watch } from 'vue';
    import MarkdownProse from './MarkdownProse.vue';
    import { Brain, ChevronDown, ChevronUp, Copy, Edit, RefreshCw, BarChart3 } from 'lucide-vue-next';

    const props = defineProps<{
        message: Message
    }>();

    const emit = defineEmits<{
        edit: [];
        reprompt: [];
    }>();

    const showReasoning = ref(false);
    const copied = ref(false);

    const shouldDisplay = computed(() => {
        return !!(props.message.content || props.message.reasoning);
    });

    const hasReasoning = computed(() => {
        return !!props.message.reasoning;
    });

    watch(() => props.message.reasoning, (newReasoning, oldReasoning) => {
        if (newReasoning && (oldReasoning || '').length === 0) {
            showReasoning.value = true;
        }
    });

    // Auto-hide reasoning when content starts appearing
    watch(() => props.message.content, (newContent) => {
        if (newContent && showReasoning.value) {
            showReasoning.value = false;
        }
    });

    const toggleReasoning = () => {
        showReasoning.value = !showReasoning.value;
    };

    const copyToClipboard = async () => {
        if (!props.message.content) return;

        try {
            await navigator.clipboard.writeText(props.message.content);
            copied.value = true;
            setTimeout(() => {
                copied.value = false;
            }, 2000);
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    };

    const handleEdit = () => {
        emit('edit');
    };

    const handleReprompt = () => {
        emit('reprompt');
    };

    const showUsage = () => {
        // TODO: Implement usage display
        console.log('Show usage for message:', props.message);
    };

</script>

<template>
    <div v-if="shouldDisplay" class="flex flex-col gap-4 max-w-3xl">
        <!-- Reasoning Section (collapsible) -->
        <div v-if="hasReasoning" class="flex flex-col gap-2">
            <button @click="toggleReasoning"
                class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors w-fit cursor-pointer">
                <Brain :size="16" />
                <span>Thinking</span>
                <ChevronDown v-if="!showReasoning" :size="16" />
                <ChevronUp v-if="showReasoning" :size="16" />
            </button>
            <div v-if="showReasoning" class="border-l-2 border-zinc-200 dark:border-zinc-700 pl-4 py-2">
                <MarkdownProse muted size="sm" :content="props.message.reasoning!" />
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col gap-4">
            <MarkdownProse v-if="props.message.content" :content="props.message.content" />
            <div v-else class="text-zinc-400 dark:text-zinc-500 italic">
                Generating response...
            </div>

            <!-- Toolbar -->
            <div v-if="props.message.content" class="flex items-center gap-2 pt-2">
                <button @click="copyToClipboard"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors cursor-pointer"
                    :class="{ 'text-green-600 dark:text-green-400': copied }">
                    <Copy :size="14" />
                    <span>{{ copied ? 'Copied!' : 'Copy' }}</span>
                </button>

                <button @click="handleReprompt"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors cursor-pointer">
                    <RefreshCw :size="14" />
                    <span>Reprompt</span>
                </button>

                <button @click="showUsage"
                    class="flex items-center gap-2 px-3 py-1.5 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors cursor-pointer">
                    <BarChart3 :size="14" />
                    <span>Usage</span>
                </button>
            </div>
        </div>
    </div>
</template>

<style></style>
