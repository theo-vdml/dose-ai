<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Github, Linkedin, Mail, Sparkles, Twitter } from 'lucide-vue-next';
</script>
<template>
    <footer class="border-t border-white/5 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-4">
                <!-- Brand -->
                <div class="lg:col-span-1">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-lg border border-white/10 bg-white/5">
                            <Sparkles class="size-5 text-white" />
                        </div>
                        <span class="text-lg font-semibold">PersonAI</span>
                    </div>
                    <p class="mb-6 text-sm text-gray-400">La plateforme de conversation IA nouvelle génération. Propulsée par OpenRouter.</p>
                    <div class="flex gap-2">
                        <a
                            href="https://twitter.com"
                            target="_blank"
                            aria-label="Suivez-nous sur Twitter"
                            class="flex size-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 ring-white/50 transition outline-none hover:bg-white/10 focus:ring-2"
                        >
                            <Twitter class="size-4 text-gray-400" />
                        </a>
                        <a
                            href="https://github.com"
                            target="_blank"
                            aria-label="Visitez notre GitHub"
                            class="flex size-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 ring-white/50 transition outline-none hover:bg-white/10 focus:ring-2"
                        >
                            <Github class="size-4 text-gray-400" />
                        </a>
                        <a
                            href="https://linkedin.com"
                            target="_blank"
                            aria-label="Rejoignez-nous sur LinkedIn"
                            class="flex size-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 ring-white/50 transition outline-none hover:bg-white/10 focus:ring-2"
                        >
                            <Linkedin class="size-4 text-gray-400" />
                        </a>
                    </div>
                </div>

                <!-- Product -->
                <div>
                    <h3 class="mb-4 text-sm font-medium text-white">Produit</h3>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a
                                href="/#features"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Fonctionnalités
                            </a>
                        </li>
                        <li>
                            <a
                                href="/#pricing"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Tarifs
                            </a>
                        </li>
                        <li>
                            <a
                                href="/#testimonials"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Témoignages
                            </a>
                        </li>
                        <li>
                            <a
                                href="/#faq"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Company -->
                <div>
                    <h3 class="mb-4 text-sm font-medium text-white">Entreprise</h3>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a
                                href="#"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                À propos
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Blog
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Carrières
                            </a>
                        </li>
                        <li>
                            <a
                                href="#"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="mb-4 text-sm font-medium text-white">Légal</h3>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <Link
                                href="/legal#1-mentions-legales"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Mentions légales
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/legal#2-politique-de-confidentialite"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Politique de confidentialité
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/legal#3-politique-de-cookies"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Politique des cookies
                            </Link>
                        </li>
                        <li>
                            <Link
                                href="/legal#4-transparence-ia-ai-act"
                                class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                            >
                                Transparence AI (AI Act)
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 border-t border-white/5 pt-8">
                <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-sm text-gray-300">© 2026 PersonAI. Tous droits réservés.</p>
                    <div class="flex items-center gap-2 text-sm text-gray-300">
                        <Mail class="size-4" />
                        <a
                            href="mailto:contact@personai.com"
                            class="rounded-xs ring-white ring-offset-4 ring-offset-black transition outline-none hover:text-white focus:ring-2"
                        >
                            contact@personai.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
