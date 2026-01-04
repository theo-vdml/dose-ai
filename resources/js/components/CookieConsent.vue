<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { Link } from '@inertiajs/vue3';

    const showBanner = ref(false);
    const showDetails = ref(false);

    const preferences = ref({
        essential: true, // Toujours activés
        analytics: false,
        performance: false,
    });

    onMounted(() => {
        // Vérifier si l'utilisateur a déjà fait un choix
        const consent = localStorage.getItem('cookie-consent');
        if (!consent) {
            showBanner.value = true;
        } else {
            const saved = JSON.parse(consent);
            preferences.value = { ...preferences.value, ...saved };
        }
    });

    const acceptAll = () => {
        preferences.value = {
            essential: true,
            analytics: true,
            performance: true,
        };
        savePreferences();
    };

    const acceptEssential = () => {
        preferences.value = {
            essential: true,
            analytics: false,
            performance: false,
        };
        savePreferences();
    };

    const saveCustomPreferences = () => {
        savePreferences();
    };

    const savePreferences = () => {
        localStorage.setItem('cookie-consent', JSON.stringify(preferences.value));
        showBanner.value = false;
        showDetails.value = false;

        // Ici vous pouvez initialiser vos trackers selon les préférences
        if (preferences.value.analytics) {
            // Initialiser analytics
            console.log('Analytics enabled');
        }
        if (preferences.value.performance) {
            // Initialiser performance tracking
            console.log('Performance tracking enabled');
        }
    };
</script>

<template>
    <Transition enter-active-class="transition-opacity duration-300" enter-from-class="opacity-0"
        enter-to-class="opacity-100" leave-active-class="transition-opacity duration-300" leave-from-class="opacity-100"
        leave-to-class="opacity-0">
        <div v-if="showBanner" class="fixed bottom-0 left-0 right-0 z-50 p-4 md:p-6">
            <div class="mx-auto max-w-7xl">
                <div class="rounded-2xl border border-zinc-800 bg-zinc-900 shadow-2xl backdrop-blur-xl">
                    <!-- Bandeau principal -->
                    <div v-if="!showDetails" class="p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex-1 space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-2xl">🍪</span>
                                    <h3 class="text-lg font-semibold text-white">Nous respectons votre vie privée</h3>
                                </div>
                                <p class="text-sm text-zinc-400">
                                    Nous utilisons des cookies pour assurer le bon fonctionnement du site et, avec votre
                                    consentement,
                                    pour analyser notre trafic. Vous pouvez accepter tous les cookies ou personnaliser
                                    vos préférences.
                                    Consultez notre <Link href="/mentions-legales#privacy"
                                        class="text-blue-400 hover:text-blue-300 underline">politique de confidentialité
                                    </Link>.
                                </p>
                            </div>
                            <div class="flex flex-col gap-2 sm:flex-row lg:flex-col xl:flex-row">
                                <button @click="showDetails = true"
                                    class="rounded-lg border border-zinc-700 bg-zinc-800 px-6 py-2.5 text-sm font-semibold text-white hover:bg-zinc-700 transition-colors whitespace-nowrap">
                                    Personnaliser
                                </button>
                                <button @click="acceptEssential"
                                    class="rounded-lg border border-zinc-700 bg-zinc-800 px-6 py-2.5 text-sm font-semibold text-white hover:bg-zinc-700 transition-colors whitespace-nowrap">
                                    Refuser
                                </button>
                                <button @click="acceptAll"
                                    class="rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-2.5 text-sm font-semibold text-white hover:from-blue-500 hover:to-cyan-500 transition-all whitespace-nowrap">
                                    Tout accepter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Détails des préférences -->
                    <div v-else class="p-6">
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-white">Personnaliser les cookies</h3>
                                    <button @click="showDetails = false"
                                        class="text-zinc-400 hover:text-white transition-colors">
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-zinc-400 mb-6">
                                    Choisissez les catégories de cookies que vous souhaitez autoriser.
                                </p>
                            </div>

                            <!-- Cookies essentiels -->
                            <div class="flex items-start gap-4 rounded-lg border border-zinc-800 bg-zinc-900/50 p-4">
                                <input type="checkbox" v-model="preferences.essential" disabled checked
                                    class="mt-1 size-5 rounded border-zinc-700 bg-zinc-800 text-blue-600 cursor-not-allowed" />
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-semibold text-white">Cookies essentiels</h4>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full bg-blue-600/20 text-blue-400">Obligatoires</span>
                                    </div>
                                    <p class="text-sm text-zinc-400">
                                        Nécessaires au fonctionnement du site (authentification, sécurité, préférences
                                        essentielles).
                                        Ces cookies ne peuvent pas être désactivés.
                                    </p>
                                </div>
                            </div>

                            <!-- Cookies analytiques -->
                            <div class="flex items-start gap-4 rounded-lg border border-zinc-800 bg-zinc-900/50 p-4">
                                <input type="checkbox" v-model="preferences.analytics"
                                    class="mt-1 size-5 rounded border-zinc-700 bg-zinc-800 text-blue-600 focus:ring-2 focus:ring-blue-600 focus:ring-offset-0 cursor-pointer" />
                                <div class="flex-1">
                                    <h4 class="font-semibold text-white mb-1">Cookies analytiques</h4>
                                    <p class="text-sm text-zinc-400">
                                        Nous permettent de comprendre comment vous utilisez notre site et d'améliorer
                                        votre expérience.
                                        Ces données sont anonymisées.
                                    </p>
                                </div>
                            </div>

                            <!-- Cookies de performance -->
                            <div class="flex items-start gap-4 rounded-lg border border-zinc-800 bg-zinc-900/50 p-4">
                                <input type="checkbox" v-model="preferences.performance"
                                    class="mt-1 size-5 rounded border-zinc-700 bg-zinc-800 text-blue-600 focus:ring-2 focus:ring-blue-600 focus:ring-offset-0 cursor-pointer" />
                                <div class="flex-1">
                                    <h4 class="font-semibold text-white mb-1">Cookies de performance</h4>
                                    <p class="text-sm text-zinc-400">
                                        Collectent des informations sur les performances du site pour nous aider à
                                        identifier et résoudre
                                        les problèmes techniques.
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-2 sm:flex-row sm:justify-between pt-4">
                                <Link href="/mentions-legales#privacy"
                                    class="text-sm text-blue-400 hover:text-blue-300 underline">
                                    En savoir plus sur notre politique de confidentialité
                                </Link>
                                <div class="flex gap-2">
                                    <button @click="showDetails = false"
                                        class="flex-1 sm:flex-none rounded-lg border border-zinc-700 bg-zinc-800 px-6 py-2.5 text-sm font-semibold text-white hover:bg-zinc-700 transition-colors">
                                        Annuler
                                    </button>
                                    <button @click="saveCustomPreferences"
                                        class="flex-1 sm:flex-none rounded-lg bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-2.5 text-sm font-semibold text-white hover:from-blue-500 hover:to-cyan-500 transition-all">
                                        Enregistrer mes préférences
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
