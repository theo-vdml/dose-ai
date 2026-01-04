<script setup lang="ts">
    import { dashboard, login, register } from '@/routes';
    import { Head, Link } from '@inertiajs/vue3';
    import { MessageSquare, Sparkles, Users, Zap } from 'lucide-vue-next';
    import CookieConsent from '@/components/CookieConsent.vue';

    withDefaults(
        defineProps<{
            canRegister: boolean;
        }>(),
        {
            canRegister: true,
        },
    );
</script>

<template>

    <Head title="Welcome to DoseAI" />
    <div class="min-h-screen bg-zinc-950 text-zinc-100">
        <!-- Header -->
        <header class="border-b border-zinc-800 backdrop-blur-sm sticky top-0 z-50 bg-zinc-950/95">
            <div class="mx-auto max-w-7xl px-6 py-4">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex items-center justify-center size-8 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <Sparkles class="size-5 text-white" />
                        </div>
                        <span class="text-xl font-bold">DoseAI</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <Link v-if="$page.props.auth.user" :href="dashboard()"
                            class="inline-block rounded-lg border border-zinc-700 bg-zinc-900 px-5 py-2 text-sm font-medium hover:bg-zinc-800 transition-colors">
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link :href="login()"
                                class="inline-block rounded-lg px-5 py-2 text-sm font-medium text-zinc-300 hover:bg-zinc-900 transition-colors">
                                Log in
                            </Link>
                            <Link v-if="canRegister" :href="register()"
                                class="inline-block rounded-lg bg-linear-to-r from-blue-600 to-cyan-600 px-5 py-2 text-sm font-medium text-white hover:from-blue-500 hover:to-cyan-500 transition-all">
                                Get Started
                            </Link>
                        </template>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div
                class="absolute inset-0 bg-linear-to-br from-blue-600/10 via-transparent to-cyan-600/10 pointer-events-none" />
            <div class="mx-auto max-w-7xl px-6 py-24 lg:py-32 relative">
                <div class="text-center space-y-8">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-zinc-800 bg-zinc-900/50 text-sm text-zinc-300">
                        <Sparkles class="size-4" />
                        <span>Powered by OpenRouter</span>
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-bold tracking-tight text-white">
                        Talk with
                        <span class="bg-linear-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent">
                            AI Personas
                        </span>
                    </h1>
                    <p class="text-xl text-zinc-400 max-w-2xl mx-auto">
                        Experience intelligent conversations with multiple AI models and unique personas.
                        Stream responses in real-time and manage your chat history effortlessly.
                    </p>
                    <div class="flex items-center justify-center gap-4 pt-4">
                        <Link v-if="$page.props.auth.user" :href="dashboard()"
                            class="inline-flex items-center gap-2 rounded-lg bg-linear-to-r from-blue-600 to-cyan-600 px-8 py-3 text-base font-semibold text-white hover:from-blue-500 hover:to-cyan-500 transition-all">
                            <MessageSquare class="size-5" />
                            <span>Start Chatting</span>
                        </Link>
                        <Link v-else :href="register()"
                            class="inline-flex items-center gap-2 rounded-lg bg-linear-to-r from-blue-600 to-cyan-600 px-8 py-3 text-base font-semibold text-white hover:from-blue-500 hover:to-cyan-500 transition-all">
                            <MessageSquare class="size-5" />
                            <span>Get Started Free</span>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-24 lg:py-32 bg-zinc-900/50">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center space-y-4 mb-16">
                    <h2 class="text-4xl font-bold tracking-tight text-white">
                        Everything you need for AI conversations
                    </h2>
                    <p class="text-lg text-zinc-400">
                        Powerful features to enhance your AI chat experience
                    </p>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Feature 1 -->
                    <div
                        class="rounded-xl border border-zinc-800 bg-zinc-900 p-8 space-y-4 hover:border-zinc-700 transition-all">
                        <div
                            class="flex items-center justify-center size-12 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <Zap class="size-6 text-white" />
                        </div>
                        <h3 class="text-xl font-semibold text-white">Real-time Streaming</h3>
                        <p class="text-zinc-400">
                            Get instant AI responses with smooth, real-time streaming. Watch thoughts unfold as they're
                            generated.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="rounded-xl border border-zinc-800 bg-zinc-900 p-8 space-y-4 hover:border-zinc-700 transition-all">
                        <div
                            class="flex items-center justify-center size-12 rounded-lg bg-gradlinearient-to-br from-blue-600 to-cyan-600">
                            <MessageSquare class="size-6 text-white" />
                        </div>
                        <h3 class="text-xl font-semibold text-white">Multiple AI Models</h3>
                        <p class="text-zinc-400">
                            Choose from a variety of AI models via OpenRouter. Pick the perfect model for your
                            conversation
                            needs.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="rounded-xl border border-zinc-800 bg-zinc-900 p-8 space-y-4 hover:border-zinc-700 transition-all">
                        <div
                            class="flex items-center justify-center size-12 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <Users class="size-6 text-white" />
                        </div>
                        <h3 class="text-xl font-semibold text-white">Unique Personas</h3>
                        <p class="text-zinc-400">
                            Chat with historical figures and characters. From Socrates to Homer Simpson, choose your
                            conversation partner.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="rounded-xl border border-zinc-800 bg-zinc-900 p-8 space-y-4 hover:border-zinc-700 transition-all">
                        <div
                            class="flex items-center justify-center size-12 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white">Conversation History</h3>
                        <p class="text-zinc-400">
                            Never lose track of your chats. All conversations are automatically saved and organized for
                            easy
                            access.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div
                        class="rounded-xl border border-zinc-800 bg-zinc-900 p-8 space-y-4 hover:border-zinc-700 transition-all">
                        <div
                            class="flex items-center justify-center size-12 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white">Custom Instructions</h3>
                        <p class="text-zinc-400">
                            Personalize your AI experience with custom instructions that persist across all your
                            conversations.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div
                        class="rounded-xl border border-zinc-800 bg-zinc-900 p-8 space-y-4 hover:border-zinc-700 transition-all">
                        <div
                            class="flex items-center justify-center size-12 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white">Built with Laravel</h3>
                        <p class="text-zinc-400">
                            Powered by Laravel 12, Inertia.js, and Vue 3. A modern, elegant, and robust tech stack.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="py-24 lg:py-32">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center space-y-4 mb-16">
                    <h2 class="text-4xl font-bold tracking-tight text-white">
                        Simple, transparent pricing
                    </h2>
                    <p class="text-lg text-zinc-400">
                        Choose the plan that fits your needs
                    </p>
                </div>

                <div class="grid gap-8 md:grid-cols-3 max-w-6xl mx-auto">
                    <!-- Starter Plan -->
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-8 space-y-6">
                        <div class="space-y-2">
                            <h3 class="text-2xl font-bold text-white">Starter</h3>
                            <p class="text-zinc-400">Perfect for individuals</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-bold text-white">$9</span>
                                <span class="text-zinc-400">/month</span>
                            </div>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>100 conversations/month</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Access to basic models</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>5 personas available</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Email support</span>
                            </li>
                        </ul>
                        <Link :href="register()"
                            class="block w-full text-center rounded-lg border border-zinc-700 bg-zinc-800 px-6 py-3 font-semibold text-white hover:bg-zinc-700 transition-colors">
                            Get Started
                        </Link>
                    </div>

                    <!-- Professional Plan (Featured) -->
                    <div class="rounded-2xl border-2 border-blue-600 bg-zinc-900 p-8 space-y-6 relative">
                        <div
                            class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-linear-to-r from-blue-600 to-cyan-600 rounded-full text-sm font-semibold text-white">
                            Most Popular
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-2xl font-bold text-white">Professional</h3>
                            <p class="text-zinc-400">For power users</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-bold text-white">$29</span>
                                <span class="text-zinc-400">/month</span>
                            </div>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Unlimited conversations</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Access to all models</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>All personas included</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Priority support</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Advanced analytics</span>
                            </li>
                        </ul>
                        <Link :href="register()"
                            class="block w-full text-center rounded-lg bg-linear-to-r from-blue-600 to-cyan-600 px-6 py-3 font-semibold text-white hover:from-blue-500 hover:to-cyan-500 transition-all">
                            Get Started
                        </Link>
                    </div>

                    <!-- Enterprise Plan -->
                    <div class="rounded-2xl border border-zinc-800 bg-zinc-900 p-8 space-y-6">
                        <div class="space-y-2">
                            <h3 class="text-2xl font-bold text-white">Enterprise</h3>
                            <p class="text-zinc-400">For organizations</p>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-bold text-white">Custom</span>
                            </div>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Unlimited everything</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Custom model training</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Custom personas</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Dedicated support</span>
                            </li>
                            <li class="flex items-center gap-3 text-zinc-300">
                                <svg class="size-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span>SLA guarantee</span>
                            </li>
                        </ul>
                        <a href="#"
                            class="block w-full text-center rounded-lg border border-zinc-700 bg-zinc-800 px-6 py-3 font-semibold text-white hover:bg-zinc-700 transition-colors">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-zinc-800 py-12 bg-zinc-900">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-col items-center justify-center gap-6 text-center">
                    <div class="flex items-center gap-2">
                        <div
                            class="flex items-center justify-center size-8 rounded-lg bg-linear-to-br from-blue-600 to-cyan-600">
                            <Sparkles class="size-5 text-white" />
                        </div>
                        <span class="text-lg font-bold text-white">DoseAI</span>
                    </div>
                    <p class="text-sm text-zinc-400">
                        Built with Laravel, Inertia.js, and Vue 3
                    </p>
                    <div class="flex items-center gap-6 text-sm">
                        <Link href="/mentions-legales" class="text-zinc-400 hover:text-white transition-colors">
                            Mentions légales
                        </Link>
                        <span class="text-zinc-700">|</span>
                        <Link href="/mentions-legales#privacy" class="text-zinc-400 hover:text-white transition-colors">
                            Privacy Policy
                        </Link>
                        <span class="text-zinc-700">|</span>
                        <Link href="/mentions-legales#ai-compliance"
                            class="text-zinc-400 hover:text-white transition-colors">
                            AI Compliance
                        </Link>
                    </div>
                    <p class="text-xs text-zinc-500">
                        © 2026 DoseAI. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        <!-- Cookie Consent Banner -->
        <CookieConsent />
    </div>

</template>
