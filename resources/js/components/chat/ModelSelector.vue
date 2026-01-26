<script setup lang="ts">
import AdvancedSelector from '@/components/chat/AdvancedSelector.vue';

interface ModelSelectorProps {
    availableModels: Model[];
}

const modelValue = defineModel<string>({ required: true });

const props = defineProps<ModelSelectorProps>();
</script>

<template>
    <AdvancedSelector
        v-model="modelValue"
        :options="props.availableModels"
        name-attr="model"
        searchable
        search-placeholder="Search models..."
        label="Select a model"
    />
</template>
