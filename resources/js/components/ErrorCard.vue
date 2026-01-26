<script lang="ts" setup>
import { AlertCircle } from 'lucide-vue-next';

const props = defineProps<{
    message: string;
    title?: string;
}>();
</script>

<template>
    <div class="group">
        <div class="relative rounded-lg border border-red-200 bg-red-50 px-4 py-4 shadow-sm dark:border-red-900/50 dark:bg-red-950/30">
            <div class="flex gap-3">
                <AlertCircle :size="20" class="mt-0.5 shrink-0 text-red-600 dark:text-red-400" />
                <div class="min-w-0 flex-1">
                    <h4 class="mb-1 text-sm font-semibold text-red-900 dark:text-red-200">{{ props.title || 'Error' }}</h4>
                    <p class="text-sm leading-relaxed text-red-700 dark:text-red-300">
                        {{ props.message }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
