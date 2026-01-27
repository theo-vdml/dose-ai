// resources/js/lib/personaIcons.ts
import { Atom, DraftingCompass, Landmark, Scroll, Swords } from 'lucide-vue-next';
import type { Component } from 'vue';

// 1. The Map Registry
export const personaIconsMap: Record<string, Component> = {
    socrates: Scroll,
    sun_tzu: Swords,
    da_vinci: DraftingCompass,
    marcus_aurelius: Landmark,
    einstein: Atom,
};

export const personaNamesMap: Record<string, string> = {
    socrates: 'Socrates',
    sun_tzu: 'Sun Tzu',
    da_vinci: 'Leonardo da Vinci',
    marcus_aurelius: 'Marcus Aurelius',
    einstein: 'Albert Einstein',
};

// 2. The Helper Function (Handles the fallback logic centrally)
export const getPersonaIcon = (personaId: string | null, defaultIcon: Component): Component => {
    return personaIconsMap[personaId || ''] || defaultIcon;
};

export const getPersonaName = (personaId: string | null): string => {
    return personaNamesMap[personaId || ''] || 'Ai Assistant';
};
