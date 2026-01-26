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

// 2. The Helper Function (Handles the fallback logic centrally)
export const getPersonaIcon = (personaId: string | null, defaultIcon: Component): Component => {
    return personaIconsMap[personaId || ''] || defaultIcon;
};
