<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

const sections = [
    {
        id: 'mentions',
        title: 'Mentions légales',
    },
    {
        id: 'privacy',
        title: 'Politique de confidentialité',
    },
    {
        id: 'ai-compliance',
        title: 'Conformité AI Act',
    },
];
</script>

<template>
    <Head title="Mentions légales - DoseAI" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100">
        <!-- Header -->
        <header class="sticky top-0 z-50 border-b border-zinc-800 bg-zinc-950/95 backdrop-blur-sm">
            <div class="mx-auto max-w-5xl px-6 py-4">
                <div class="flex items-center gap-4">
                    <Link
                        href="/"
                        class="inline-flex items-center gap-2 text-zinc-400 transition-colors outline-none hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black"
                    >
                        <ArrowLeft class="size-4" />
                        <span>Retour</span>
                    </Link>
                    <span class="text-zinc-700">|</span>
                    <h1 class="text-xl font-bold">Mentions légales</h1>
                </div>
            </div>
        </header>

        <div class="mx-auto max-w-5xl px-6 py-12">
            <!-- Navigation rapide -->
            <div class="mb-12 rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                <h2 class="mb-4 text-sm font-semibold tracking-wider text-zinc-400 uppercase">Sommaire</h2>
                <div class="grid gap-3 md:grid-cols-3">
                    <a
                        v-for="section in sections"
                        :key="section.id"
                        :href="`#${section.id}`"
                        class="rounded-lg border border-zinc-800 bg-zinc-900 p-4 transition-all outline-none hover:border-zinc-700 hover:bg-zinc-800 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black"
                    >
                        <span class="text-sm font-medium text-white">{{ section.title }}</span>
                    </a>
                </div>
            </div>

            <!-- Mentions légales -->
            <section id="mentions" class="mb-16 scroll-mt-20">
                <div class="mb-8">
                    <h2 class="mb-4 text-3xl font-bold text-white">Mentions légales</h2>
                    <div class="h-px bg-zinc-800"></div>
                </div>

                <div class="space-y-6">
                    <!-- Éditeur -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Éditeur du site</h3>
                        <div class="space-y-2 text-zinc-300">
                            <p><strong class="text-white">Raison sociale :</strong> DoseAI SAS</p>
                            <p><strong class="text-white">Capital social :</strong> 50 000 €</p>
                            <p><strong class="text-white">Siège social :</strong> 42 Avenue de l'Innovation, 75001 Paris, France</p>
                            <p><strong class="text-white">SIRET :</strong> 123 456 789 00010</p>
                            <p><strong class="text-white">RCS :</strong> Paris B 123 456 789</p>
                            <p><strong class="text-white">TVA intracommunautaire :</strong> FR 12 123456789</p>
                            <p>
                                <strong class="text-white">Email :</strong>
                                <a href="mailto:contact@doseai.fr" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">contact@doseai.fr</a>
                            </p>
                            <p><strong class="text-white">Téléphone :</strong> +33 1 23 45 67 89</p>
                        </div>
                    </div>

                    <!-- Directeur de publication -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Directeur de publication</h3>
                        <div class="space-y-2 text-zinc-300">
                            <p><strong class="text-white">Nom :</strong> Jean Dupont</p>
                            <p><strong class="text-white">Fonction :</strong> Président de DoseAI SAS</p>
                            <p>
                                <strong class="text-white">Contact :</strong>
                                <a href="mailto:direction@doseai.fr" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">direction@doseai.fr</a>
                            </p>
                        </div>
                    </div>

                    <!-- Hébergeur -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Hébergement</h3>
                        <div class="space-y-2 text-zinc-300">
                            <p><strong class="text-white">Hébergeur :</strong> Hostinger International Ltd.</p>
                            <p><strong class="text-white">Adresse :</strong> 61 Lordou Vironos Street, 6023 Larnaca, Chypre</p>
                            <p>
                                <strong class="text-white">Site web :</strong>
                                <a href="https://www.hostinger.fr" target="_blank" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">www.hostinger.fr</a>
                            </p>
                        </div>
                    </div>

                    <!-- Propriété intellectuelle -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Propriété intellectuelle</h3>
                        <p class="text-zinc-300">
                            L'ensemble du contenu de ce site (textes, images, vidéos, logos, etc.) est la propriété exclusive de DoseAI SAS, sauf mention
                            contraire. Toute reproduction, distribution, modification, adaptation, retransmission ou publication de ces différents éléments est
                            strictement interdite sans l'accord exprès par écrit de DoseAI SAS.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Politique de confidentialité -->
            <section id="privacy" class="mb-16 scroll-mt-20">
                <div class="mb-8">
                    <h2 class="mb-4 text-3xl font-bold text-white">Politique de confidentialité</h2>
                    <div class="h-px bg-zinc-800"></div>
                </div>

                <div class="space-y-6">
                    <!-- Responsable du traitement -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Responsable du traitement</h3>
                        <p class="mb-4 text-zinc-300">DoseAI SAS est le responsable du traitement de vos données personnelles.</p>
                        <p class="text-zinc-300">
                            <strong class="text-white">DPO (Délégué à la Protection des Données) :</strong><br />
                            Email : <a href="mailto:dpo@doseai.fr" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">dpo@doseai.fr</a><br />
                            Adresse : 42 Avenue de l'Innovation, 75001 Paris, France
                        </p>
                    </div>

                    <!-- Données collectées -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Données personnelles collectées</h3>
                        <div class="space-y-4 text-zinc-300">
                            <div>
                                <h4 class="mb-2 font-semibold text-white">Lors de la création de compte :</h4>
                                <ul class="ml-4 list-inside list-disc space-y-1">
                                    <li>Nom et prénom</li>
                                    <li>Adresse email</li>
                                    <li>Mot de passe (chiffré)</li>
                                    <li>Date de création du compte</li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="mb-2 font-semibold text-white">Lors de l'utilisation du service :</h4>
                                <ul class="ml-4 list-inside list-disc space-y-1">
                                    <li>Contenu des conversations avec l'IA</li>
                                    <li>Préférences utilisateur (modèle IA sélectionné, personas)</li>
                                    <li>Historique des conversations</li>
                                    <li>Données de connexion (adresse IP, user-agent, horodatage)</li>
                                    <li>Cookies et traceurs (avec votre consentement)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Finalités -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Finalités du traitement</h3>
                        <div class="space-y-3 text-zinc-300">
                            <div class="flex gap-3">
                                <span class="text-blue-400">•</span>
                                <div>
                                    <strong class="text-white">Gestion du compte utilisateur</strong> - Création, authentification et gestion de votre profil
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-blue-400">•</span>
                                <div>
                                    <strong class="text-white">Fourniture du service</strong> - Traitement de vos requêtes par l'IA et stockage de vos
                                    conversations
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-blue-400">•</span>
                                <div><strong class="text-white">Amélioration du service</strong> - Analyse des usages pour améliorer nos fonctionnalités</div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-blue-400">•</span>
                                <div>
                                    <strong class="text-white">Communication</strong> - Envoi d'informations relatives au service (avec votre consentement pour
                                    les communications marketing)
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span class="text-blue-400">•</span>
                                <div><strong class="text-white">Sécurité</strong> - Prévention de la fraude et protection de nos systèmes</div>
                            </div>
                        </div>
                    </div>

                    <!-- Base légale -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Base légale du traitement</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p>
                                <strong class="text-white">Exécution du contrat :</strong> Fourniture du service DoseAI selon les conditions générales
                                d'utilisation
                            </p>
                            <p><strong class="text-white">Consentement :</strong> Cookies non essentiels, communications marketing</p>
                            <p><strong class="text-white">Intérêt légitime :</strong> Amélioration du service, sécurité, statistiques</p>
                            <p><strong class="text-white">Obligation légale :</strong> Conservation des données de connexion conformément à la loi</p>
                        </div>
                    </div>

                    <!-- Durée de conservation -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Durée de conservation</h3>
                        <div class="space-y-2 text-zinc-300">
                            <p><strong class="text-white">Données de compte :</strong> Jusqu'à la suppression du compte + 1 an</p>
                            <p>
                                <strong class="text-white">Conversations :</strong> Conservées tant que le compte est actif (suppression possible à tout moment)
                            </p>
                            <p><strong class="text-white">Logs de connexion :</strong> 12 mois (obligation légale)</p>
                            <p><strong class="text-white">Cookies :</strong> 13 mois maximum</p>
                        </div>
                    </div>

                    <!-- Destinataires -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Destinataires des données</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p><strong class="text-white">Personnel autorisé de DoseAI :</strong> Accès strictement limité et sécurisé</p>
                            <p><strong class="text-white">Prestataires techniques :</strong></p>
                            <ul class="ml-4 list-inside list-disc space-y-1">
                                <li>Hébergeur (Hostinger - Chypre)</li>
                                <li>Fournisseur d'API IA (OpenRouter - États-Unis, voir section transferts)</li>
                                <li>Service d'emailing (si applicable)</li>
                            </ul>
                            <p class="mt-4 border-l-2 border-zinc-700 pl-4 text-sm">
                                <strong class="text-white">Important :</strong> Vos conversations peuvent être transmises à des fournisseurs d'IA tiers via
                                OpenRouter pour traitement. Ces données peuvent être hébergées en dehors de l'UE.
                            </p>
                        </div>
                    </div>

                    <!-- Transferts hors UE -->
                    <div class="rounded-xl border border-amber-600 bg-amber-950/20 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Transferts hors UE</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p>
                                <strong class="text-white">Utilisation de l'API OpenRouter :</strong>
                                Pour fournir notre service, nous utilisons l'API OpenRouter qui peut transmettre vos conversations à différents fournisseurs de
                                modèles d'IA situés en dehors de l'Union Européenne, notamment aux États-Unis.
                            </p>
                            <p>
                                <strong class="text-white">Garanties :</strong>
                                Ces transferts sont encadrés par des clauses contractuelles types approuvées par la Commission Européenne. OpenRouter s'engage à
                                respecter des standards de protection des données équivalents au RGPD.
                            </p>
                            <p class="text-sm">
                                Pour plus d'informations sur les garanties mises en place, contactez notre DPO :
                                <a href="mailto:dpo@doseai.fr" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">dpo@doseai.fr</a>
                            </p>
                        </div>
                    </div>

                    <!-- Droits des utilisateurs -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Vos droits</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p>Conformément au RGPD, vous disposez des droits suivants :</p>
                            <div class="mt-4 grid gap-3 md:grid-cols-2">
                                <div class="rounded-lg border border-zinc-800 p-4">
                                    <strong class="text-white">Droit d'accès</strong>
                                    <p class="mt-1 text-sm">Obtenir une copie de vos données</p>
                                </div>
                                <div class="rounded-lg border border-zinc-800 p-4">
                                    <strong class="text-white">Droit de rectification</strong>
                                    <p class="mt-1 text-sm">Corriger vos données inexactes</p>
                                </div>
                                <div class="rounded-lg border border-zinc-800 p-4">
                                    <strong class="text-white">Droit à l'effacement</strong>
                                    <p class="mt-1 text-sm">Supprimer vos données</p>
                                </div>
                                <div class="rounded-lg border border-zinc-800 p-4">
                                    <strong class="text-white">Droit à la portabilité</strong>
                                    <p class="mt-1 text-sm">Récupérer vos données dans un format structuré</p>
                                </div>
                                <div class="rounded-lg border border-zinc-800 p-4">
                                    <strong class="text-white">Droit d'opposition</strong>
                                    <p class="mt-1 text-sm">Vous opposer à certains traitements</p>
                                </div>
                                <div class="rounded-lg border border-zinc-800 p-4">
                                    <strong class="text-white">Droit à la limitation</strong>
                                    <p class="mt-1 text-sm">Limiter le traitement de vos données</p>
                                </div>
                            </div>
                            <p class="mt-6">
                                <strong class="text-white">Comment exercer vos droits ?</strong><br />
                                Contactez notre DPO par email : <a href="mailto:dpo@doseai.fr" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">dpo@doseai.fr</a><br />
                                Réponse garantie sous 30 jours.
                            </p>
                            <p class="text-sm italic">
                                Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la CNIL (<a
                                    href="https://www.cnil.fr"
                                    target="_blank"
                                    class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none"
                                    >www.cnil.fr</a
                                >).
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Conformité AI Act -->
            <section id="ai-compliance" class="mb-16 scroll-mt-20">
                <div class="mb-8">
                    <h2 class="mb-4 text-3xl font-bold text-white">Conformité AI Act & Transparence IA</h2>
                    <div class="h-px bg-zinc-800"></div>
                </div>

                <div class="space-y-6">
                    <!-- Transparence -->
                    <div class="rounded-xl border-2 border-blue-600 bg-blue-950/20 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Transparence sur l'utilisation de l'IA</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p class="text-lg font-semibold text-white">Vous interagissez avec un système d'intelligence artificielle générative.</p>
                            <p>
                                DoseAI est une plateforme qui vous permet de converser avec différents modèles d'IA via l'API OpenRouter.
                                <strong class="text-white">Vous n'interagissez pas avec un être humain</strong>, mais avec un système automatisé basé sur
                                l'apprentissage automatique (machine learning).
                            </p>
                        </div>
                    </div>

                    <!-- Modèles utilisés -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Modèles d'IA utilisés</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p>DoseAI utilise l'API OpenRouter qui donne accès à une variété de modèles d'IA provenant de différents fournisseurs :</p>
                            <ul class="ml-4 list-inside list-disc space-y-1">
                                <li>OpenAI (GPT-4, GPT-3.5, etc.)</li>
                                <li>Anthropic (Claude)</li>
                                <li>Google (PaLM, Gemini)</li>
                                <li>Meta (LLaMA)</li>
                                <li>Autres modèles open-source et propriétaires</li>
                            </ul>
                            <p class="mt-4">
                                <strong class="text-white">Vous choisissez le modèle</strong> que vous souhaitez utiliser au début de chaque conversation.
                                Chaque modèle a ses propres caractéristiques et peut produire des résultats différents.
                            </p>
                        </div>
                    </div>

                    <!-- Limites et disclaimer -->
                    <div class="rounded-xl border-2 border-amber-600 bg-amber-950/20 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Limites du système et avertissements</h3>
                        <div class="space-y-4 text-zinc-300">
                            <div class="border-l-4 border-amber-600 pl-4">
                                <p class="mb-2 font-semibold text-white">Hallucinations possibles</p>
                                <p>
                                    Les modèles d'IA peuvent générer des informations inexactes ou trompeuses appelées "hallucinations".
                                    <strong class="text-white">Ne considérez pas les réponses comme des faits absolus</strong> et vérifiez toujours les
                                    informations importantes auprès de sources fiables.
                                </p>
                            </div>

                            <div class="border-l-4 border-amber-600 pl-4">
                                <p class="mb-2 font-semibold text-white">Ne remplace pas un professionnel</p>
                                <p>
                                    DoseAI <strong class="text-white">ne remplace en aucun cas l'avis d'un professionnel</strong> (médecin, avocat, conseiller
                                    financier, etc.). Les réponses fournies sont à titre informatif uniquement et ne constituent pas des conseils
                                    professionnels.
                                </p>
                            </div>

                            <div class="border-l-4 border-amber-600 pl-4">
                                <p class="mb-2 font-semibold text-white">Biais potentiels</p>
                                <p>
                                    Les modèles d'IA peuvent refléter des biais présents dans leurs données d'entraînement. Nous travaillons constamment pour
                                    identifier et minimiser ces biais, mais ils peuvent néanmoins affecter les réponses générées.
                                </p>
                            </div>

                            <div class="border-l-4 border-amber-600 pl-4">
                                <p class="mb-2 font-semibold text-white">Connaissances limitées</p>
                                <p>
                                    Les modèles d'IA ont été entraînés sur des données jusqu'à une certaine date et peuvent ne pas avoir connaissance
                                    d'événements récents ou de développements très spécialisés.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Utilisation des conversations -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Utilisation des conversations</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p>
                                <strong class="text-white">Amélioration du service :</strong>
                                Vos conversations peuvent être analysées de manière agrégée et anonymisée pour améliorer nos services, identifier les bugs et
                                optimiser l'expérience utilisateur.
                            </p>
                            <p>
                                <strong class="text-white">Transmission aux fournisseurs d'IA :</strong>
                                Vos messages sont transmis aux fournisseurs de modèles d'IA (via OpenRouter) pour traitement. Consultez les politiques de
                                confidentialité respectives de ces fournisseurs :
                            </p>
                            <ul class="ml-4 list-inside list-disc space-y-1 text-sm">
                                <li>
                                    <a href="https://openrouter.ai/privacy" target="_blank" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none"
                                        >OpenRouter Privacy Policy</a
                                    >
                                </li>
                                <li>
                                    <a href="https://openai.com/policies/privacy-policy" target="_blank" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none"
                                        >OpenAI Privacy Policy</a
                                    >
                                </li>
                                <li>
                                    <a href="https://www.anthropic.com/privacy" target="_blank" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none"
                                        >Anthropic Privacy Policy</a
                                    >
                                </li>
                            </ul>
                            <p class="mt-4 text-sm italic">
                                <strong class="text-white">Important :</strong> Ne partagez jamais d'informations sensibles, confidentielles ou personnelles
                                dans vos conversations (mots de passe, données bancaires, informations médicales, etc.).
                            </p>
                        </div>
                    </div>

                    <!-- Contrôle utilisateur -->
                    <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                        <h3 class="mb-4 text-xl font-semibold text-white">Votre contrôle</h3>
                        <div class="space-y-3 text-zinc-300">
                            <p>Vous avez un contrôle total sur vos données :</p>
                            <ul class="ml-4 list-inside list-disc space-y-1">
                                <li>Vous pouvez supprimer vos conversations à tout moment depuis votre tableau de bord</li>
                                <li>Vous pouvez exporter vos données (droit à la portabilité)</li>
                                <li>Vous pouvez supprimer votre compte et toutes vos données associées</li>
                                <li>Vous pouvez choisir le modèle d'IA que vous utilisez</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cookies -->
            <section class="mb-16">
                <div class="mb-8">
                    <h2 class="mb-4 text-3xl font-bold text-white">Politique de cookies</h2>
                    <div class="h-px bg-zinc-800"></div>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-6">
                    <div class="space-y-4 text-zinc-300">
                        <p>DoseAI utilise des cookies et technologies similaires pour assurer le bon fonctionnement du site et améliorer votre expérience.</p>

                        <div class="mt-4 space-y-3">
                            <div>
                                <strong class="text-white">Cookies essentiels (ne nécessitent pas de consentement) :</strong>
                                <ul class="mt-2 ml-4 list-inside list-disc space-y-1">
                                    <li>Cookies d'authentification et de session</li>
                                    <li>Cookies de sécurité</li>
                                    <li>Cookies de préférences essentielles</li>
                                </ul>
                            </div>

                            <div>
                                <strong class="text-white">Cookies non essentiels (nécessitent votre consentement) :</strong>
                                <ul class="mt-2 ml-4 list-inside list-disc space-y-1">
                                    <li>Cookies analytiques (statistiques d'utilisation)</li>
                                    <li>Cookies de performance</li>
                                </ul>
                            </div>
                        </div>

                        <p class="mt-4 text-sm">
                            Vous pouvez modifier vos préférences de cookies à tout moment via les paramètres de votre navigateur ou en nous contactant à
                            <a href="mailto:dpo@doseai.fr" class="text-blue-400 hover:text-blue-300 focus:ring-white focus:ring-offset-4 focus:ring-offset-black focus:ring-2 outline-none">dpo@doseai.fr</a>.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact -->
            <section class="mb-16">
                <div class="rounded-xl border border-zinc-800 bg-zinc-900 p-8">
                    <h2 class="mb-4 text-2xl font-bold text-white">Contact</h2>
                    <p class="mb-6 text-zinc-300">Pour toute question concernant vos données personnelles ou l'utilisation de DoseAI :</p>
                    <div class="space-y-3">
                        <div class="flex items-baseline gap-3">
                            <span class="w-32 shrink-0 font-medium text-zinc-400">Contact général :</span>
                            <a href="mailto:contact@doseai.fr" class="text-blue-400 underline hover:text-blue-300"> contact@doseai.fr </a>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="w-32 shrink-0 font-medium text-zinc-400">DPO :</span>
                            <a href="mailto:dpo@doseai.fr" class="text-blue-400 underline hover:text-blue-300"> dpo@doseai.fr </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mise à jour -->
            <div class="text-center text-sm text-zinc-500">
                <p>Dernière mise à jour : 4 janvier 2026</p>
                <p class="mt-2">Version 1.0</p>
            </div>
        </div>
    </div>
</template>
