<script lang="ts" setup>
    import { computed, ref, watch } from 'vue';
    import MarkdownProse from './MarkdownProse.vue';
    import { Brain, ChevronDown, ChevronUp, Copy, RefreshCw, BarChart3, ChevronRight, AlertCircle } from 'lucide-vue-next';
    import PendingIndicator from '../PendingIndicator.vue';
    import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
    import ErrorCard from '../ErrorCard.vue';

    const props = defineProps<{
        message: Message
    }>();

    const emit = defineEmits<{
        edit: [];
        reprompt: [];
    }>();

    const copied = ref(false);

    const copyToClipboard = async () => {
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

</script>

<template>
    <div class="flex flex-col gap-4 max-w-3xl">

        <!-- Pending status (...) when waiting for a response -->
        <PendingIndicator v-if="props.message.state === 'pending'" />

        <!-- Reasoning Section -->
        <Disclosure v-if="props.message.reasoning" v-slot="{ open, close }" as="div">
            <DisclosureButton class="w-full group">
                <div class="flex items-center gap-2  ext-zinc-600 dark:text-zinc-400  cursor-pointer">
                    <Brain :size="16"
                        class="shrink-0 text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-700 dark:group-hover:text-zinc-300 transition-colors" />
                    <span class="text-sm font-medium">Reasoning</span>
                    <ChevronDown v-if="open" :size="16" />
                    <ChevronRight v-else :size="16" />
                </div>
            </DisclosureButton>
            <transition enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in" leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2">
                <DisclosurePanel class="mt-3">
                    <div
                        class="px-4 py-3 bg-zinc-50/50 dark:bg-zinc-900/30 rounded-lg border border-zinc-200 dark:border-zinc-800">
                        <MarkdownProse muted size="sm" :content="props.message.reasoning" />
                    </div>
                </DisclosurePanel>
            </transition>
        </Disclosure>

        <!-- Main Content -->
        <MarkdownProse v-if="props.message.content" :content="props.message.content" />

        <!-- Error Message -->
        <ErrorCard v-if="props.message.error" :message="props.message.error" title="Assistant Error" />

        <!-- Toolbar -->
        <div v-if="props.message.state === 'completed'" class="flex items-center gap-2 pt-2">
            <button @click="copyToClipboard"
                class="flex items-center gap-2 px-3 py-1.5 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-md transition-colors cursor-pointer"
                :class="{ 'text-green-600 dark:text-green-400': copied }">
                <Copy :size="14" />
                <span>{{ copied ? 'Copied!' : 'Copy' }}</span>
            </button>
        </div>
    </div>
</template>

<style></style>
