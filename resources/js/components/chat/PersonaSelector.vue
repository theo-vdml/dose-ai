<script setup lang="ts">
import AdvancedSelector from '@/components/chat/AdvancedSelector.vue';
import { getPersonaIcon } from '@/lib/personaIcons';
import { CircleDashed } from 'lucide-vue-next';

interface Persona {
    id: string;
    name: string;
    description?: string;
}

interface PersonaSelectorProps {
    availablePersonas: Persona[];
}

const modelValue = defineModel<string|null>({ required: true });

const props = defineProps<PersonaSelectorProps>();

const personasWithIcon = props.availablePersonas.map((persona) => ({
    ...persona,
    icon: getPersonaIcon(persona.id, CircleDashed), // Placeholder icon, replace with actual icon logic if needed
}));
</script>

<template>
    <AdvancedSelector
        v-model="modelValue"
        :options="personasWithIcon"
        name-attr="persona_id"
        title="Select a Persona"
        description="Choose an assistant Persona or start without one"
        label="Select Persona"
    />
</template>
