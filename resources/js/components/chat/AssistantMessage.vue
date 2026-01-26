<script lang="ts" setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { Brain, ChevronDown, ChevronRight, Copy } from 'lucide-vue-next';
import { ref } from 'vue';
import ErrorCard from '../ErrorCard.vue';
import PendingIndicator from '../PendingIndicator.vue';
import MarkdownProse from './MarkdownProse.vue';

const props = defineProps<{
    message: Message;
}>();

// const emit = defineEmits<{
//     edit: [];
//     reprompt: [];
// }>();

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
    <div class="flex max-w-3xl flex-col gap-4">
        <!-- Pending status (...) when waiting for a response -->
        <PendingIndicator v-if="props.message.state === 'pending'" />

        <!-- Reasoning Section -->
        <Disclosure v-if="props.message.reasoning" v-slot="{ open }" as="div">
            <DisclosureButton class="group w-full">
                <div class="ext-zinc-600 flex cursor-pointer items-center gap-2 dark:text-zinc-400">
                    <Brain
                        :size="16"
                        class="shrink-0 text-zinc-500 transition-colors group-hover:text-zinc-700 dark:text-zinc-400 dark:group-hover:text-zinc-300"
                    />
                    <span class="text-sm font-medium">Reasoning</span>
                    <ChevronDown v-if="open" :size="16" />
                    <ChevronRight v-else :size="16" />
                </div>
            </DisclosureButton>
            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <DisclosurePanel class="mt-3">
                    <div class="rounded-lg border border-zinc-200 bg-zinc-50/50 px-4 py-3 dark:border-zinc-800 dark:bg-zinc-900/30">
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
            <button
                @click="copyToClipboard"
                class="flex cursor-pointer items-center gap-2 rounded-md px-3 py-1.5 text-sm text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100"
                :class="{ 'text-green-600 dark:text-green-400': copied }"
            >
                <Copy :size="14" />
                <span>{{ copied ? 'Copied!' : 'Copy' }}</span>
            </button>
        </div>
    </div>
</template>

<style></style>
