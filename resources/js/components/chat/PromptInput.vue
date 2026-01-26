<script setup lang="ts">
import { useVModel } from '@vueuse/core';
import { ArrowUp, Square } from 'lucide-vue-next';
import { computed } from 'vue';
import Textarea from '../Textarea.vue';

interface PromptInputProps {
    message: string;
    disabled?: boolean;
    cancelable?: boolean;
    maxLines?: number;
    placeholder?: string;
    label?: string;
}

interface PromptInputEmits {
    (e: 'update:message', value: string): void;
    (e: 'submit'): void;
    (e: 'cancel'): void;
}

const props = withDefaults(defineProps<PromptInputProps>(), {
    message: '',
    disabled: false,
    cancelable: false,
    maxLines: 10,
    placeholder: 'Type your message here...',
    label: 'Message Input',
});

const emit = defineEmits<PromptInputEmits>();

const messageValue = useVModel(props, 'message', emit);

const actionLabel = computed(() => (props.cancelable ? 'Cancel message' : 'Send message'));

const canSubmit = computed(() => {
    return !props.disabled && !props.cancelable && messageValue.value.trim().length > 0;
});

const handleKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        if (canSubmit.value) {
            onAction();
        }
    }
};

const onAction = () => {
    if (props.cancelable) {
        emit('cancel');
    } else {
        emit('submit');
    }
};
</script>

<template>
    <div class="">
        <!-- Main Input Container -->
        <div class="relative rounded-3xl border bg-card shadow-sm transition-shadow focus-within:ring focus-within:ring-ring hover:shadow-md">
            <!-- Textarea -->
            <Textarea
                id="message-input"
                name="message"
                v-model="messageValue"
                :placeholder="props.placeholder"
                :max-lines="props.maxLines"
                :aria-label="props.label"
                @keydown="handleKeyDown"
                class="px-6 py-5 text-foreground placeholder:text-muted-foreground"
            />

            <!-- Bottom Toolbar -->
            <div class="flex items-center gap-3 px-4 pb-4">
                <div class="flex flex-1 items-center gap-4">
                    <slot />
                </div>

                <!-- Submit/Cancel Button -->
                <button
                    type="button"
                    @click="onAction"
                    :disabled="props.disabled && !props.cancelable"
                    :aria-label="actionLabel"
                    :title="actionLabel"
                    class="flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-full bg-primary text-primary-foreground transition-colors hover:bg-primary/90 focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:pointer-events-none disabled:opacity-50"
                >
                    <Square v-if="props.cancelable" class="h-4 w-4" aria-hidden="true" />
                    <ArrowUp v-else class="h-4 w-4" aria-hidden="true" />
                </button>
            </div>
        </div>
    </div>
</template>
