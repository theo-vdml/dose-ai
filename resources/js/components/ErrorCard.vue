<script lang="ts" setup>

    import { AlertCircle } from 'lucide-vue-next';

    const props = defineProps<{
        message: string;
        title?: string;
    }>();

</script>

<template>
    <div class="group">
        <div
            class="relative px-4 py-4 bg-red-50 dark:bg-red-950/30 rounded-lg border border-red-200 dark:border-red-900/50 shadow-sm">
            <div class="flex gap-3">
                <AlertCircle :size="20" class="shrink-0 text-red-600 dark:text-red-400 mt-0.5" />
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-red-900 dark:text-red-200 mb-1">{{ props.title || 'Error' }}
                    </h4>
                    <p class="text-sm text-red-700 dark:text-red-300 leading-relaxed">
                        {{ props.message }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
