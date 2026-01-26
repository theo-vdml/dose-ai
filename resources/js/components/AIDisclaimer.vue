<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const isDismissed = ref(false);

const dismiss = () => {
    isDismissed.value = true;
    sessionStorage.setItem('ai-disclaimer-dismissed', 'true');
};

// Vérifier si déjà dismissed dans cette session
if (typeof window !== 'undefined') {
    isDismissed.value = sessionStorage.getItem('ai-disclaimer-dismissed') === 'true';
}
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
    >
        <div v-if="!isDismissed" class="mb-6 rounded-xl border-2 border-amber-600/50 bg-amber-950/20 p-6">
            <div class="flex gap-4">
                <div class="shrink-0 text-3xl">🤖</div>
                <div class="flex-1 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-white">Vous interagissez avec une Intelligence Artificielle</h3>
                            <p class="text-sm text-zinc-400">Conformité AI Act & Transparence</p>
                        </div>
                        <button @click="dismiss" class="text-zinc-400 transition-colors hover:text-white" title="Masquer pour cette session">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2 text-sm text-zinc-300">
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 text-amber-500">⚠️</span>
                            <p>
                                <strong class="text-white">Hallucinations possibles :</strong> Les réponses peuvent contenir des informations inexactes.
                                Vérifiez toujours les informations importantes.
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 text-amber-500">⚠️</span>
                            <p>
                                <strong class="text-white">Ne remplace pas un professionnel :</strong> Ce système ne peut se substituer à l'avis d'un médecin,
                                avocat ou autre professionnel qualifié.
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="mt-0.5 text-blue-500">ℹ️</span>
                            <p>
                                <strong class="text-white">Confidentialité :</strong> Ne partagez jamais d'informations sensibles (mots de passe, données
                                bancaires, informations médicales).
                            </p>
                        </div>
                    </div>

                    <div class="pt-2">
                        <Link href="/mentions-legales#ai-compliance" class="inline-flex items-center gap-1 text-sm text-blue-400 underline hover:text-blue-300">
                            En savoir plus sur nos limites et engagements
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
