<script setup lang="ts">
import { login, register } from '@/routes';
import { newMethod } from '@/routes/conversations';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { CheckCircle2, ChevronDown, Clock, Github, Linkedin, Mail, MessageSquare, Shield, Sparkles, Star, Twitter, Users, Zap } from 'lucide-vue-next';
import { ref } from 'vue';

const openFaq = ref<number | null>(null);

const toggleFaq = (index: number) => {
    openFaq.value = openFaq.value === index ? null : index;
};

const features = [
    {
        icon: Zap,
        title: 'Streaming en temps réel',
        description: 'Recevez des réponses instantanées avec un streaming fluide et réactif.',
    },
    {
        icon: Users,
        title: 'Personas multiples',
        description: 'Conversez avec des personnalités historiques et des personnages uniques.',
    },
    {
        icon: MessageSquare,
        title: 'Modèles IA variés',
        description: 'Accédez à plusieurs modèles IA via OpenRouter pour tous vos besoins.',
    },
    {
        icon: Shield,
        title: 'Sécurisé & privé',
        description: 'Vos conversations sont chiffrées et stockées en toute sécurité.',
    },
    {
        icon: Clock,
        title: 'Historique complet',
        description: 'Retrouvez facilement toutes vos conversations passées.',
    },
    {
        icon: Sparkles,
        title: 'Personnalisable',
        description: "Instructions personnalisées pour adapter l'IA à vos besoins.",
    },
];

const plans = [
    {
        name: 'Free',
        price: '0€',
        period: '/mois',
        description: 'Parfait pour découvrir',
        features: ['50 messages par jour', 'Accès aux modèles de base', '3 personas disponibles', 'Historique 7 jours', 'Support communautaire'],
        cta: 'Commencer gratuitement',
        highlighted: false,
    },
    {
        name: 'Pro',
        price: '19€',
        period: '/mois',
        description: 'Pour les utilisateurs réguliers',
        features: [
            'Messages illimités',
            'Tous les modèles IA premium',
            'Tous les personas',
            'Historique illimité',
            'Support prioritaire',
            'Export des conversations',
        ],
        cta: 'Essayer Pro',
        highlighted: true,
    },
    {
        name: 'Enterprise',
        price: 'Sur mesure',
        period: '',
        description: 'Pour les équipes et organisations',
        features: ['Tout du plan Pro', 'API dédiée', 'Modèles personnalisés', 'SSO & Authentification avancée', 'SLA garanti', 'Account manager dédié'],
        cta: 'Nous contacter',
        highlighted: false,
    },
];

const testimonials = [
    {
        name: 'Sophie Dubois',
        role: 'Chef de projet digital',
        company: 'TechCorp',
        content:
            "PersonAI a transformé notre façon de travailler. Les personas historiques nous permettent d'aborder les problèmes sous différents angles. C'est devenu un outil indispensable.",
        rating: 5,
    },
    {
        name: 'Marc Lefebvre',
        role: 'Développeur Full-Stack',
        company: 'StartupLab',
        content:
            "L'intégration avec OpenRouter offre une flexibilité incroyable. Je peux choisir le modèle parfait pour chaque tâche. Le streaming en temps réel est ultra-réactif.",
        rating: 5,
    },
    {
        name: 'Claire Martin',
        role: 'Content Manager',
        company: 'MediaGroup',
        content:
            "Interface élégante et intuitive. L'historique des conversations me permet de retrouver instantanément toutes mes références. Un gain de temps considérable au quotidien.",
        rating: 5,
    },
];

const faqs = [
    {
        question: "Qu'est-ce que PersonAI exactement ?",
        answer: "PersonAI est une plateforme de conversation IA qui vous permet de dialoguer avec différents modèles d'intelligence artificielle à travers des personas uniques. Propulsé par OpenRouter, vous avez accès aux meilleurs modèles du marché dans une interface moderne et intuitive.",
    },
    {
        question: 'Quels modèles IA sont disponibles ?',
        answer: "Via OpenRouter, nous offrons un accès à plus de 20 modèles IA incluant GPT-4, Claude, Llama, Mistral et bien d'autres. Les abonnés Pro ont accès à tous les modèles, tandis que le plan gratuit inclut une sélection de modèles de base performants.",
    },
    {
        question: 'Comment fonctionnent les personas ?',
        answer: 'Les personas sont des personnalités prédéfinies qui donnent un contexte et un style unique à vos conversations. Vous pouvez dialoguer avec Socrate pour de la philosophie, Einstein pour des questions scientifiques, ou même Homer Simpson pour une conversation plus décontractée.',
    },
    {
        question: 'Mes données sont-elles sécurisées ?',
        answer: 'Absolument. Toutes vos conversations sont chiffrées en transit (HTTPS/TLS) et au repos. Nous ne partageons jamais vos données avec des tiers. Vous pouvez supprimer votre historique à tout moment. Notre infrastructure respecte le RGPD et les standards de sécurité les plus stricts.',
    },
    {
        question: 'Puis-je annuler mon abonnement à tout moment ?',
        answer: "Oui, vous pouvez annuler votre abonnement à tout moment depuis votre tableau de bord. Aucun engagement, aucune question posée. Si vous annulez, vous garderez l'accès jusqu'à la fin de votre période de facturation en cours.",
    },
    {
        question: 'Y a-t-il une API disponible ?',
        answer: "Une API REST complète est disponible pour les abonnés Enterprise. Elle permet d'intégrer PersonAI dans vos applications et workflows existants. Contactez notre équipe commerciale pour plus d'informations sur les tarifs et les fonctionnalités de l'API.",
    },
];

const page = usePage();

const appUrl = page.props.app_url;
const appName = page.props.name;
const mainImage = `${appUrl}/images/og-image.png`;

const seo = {
    title: 'PersonAI - Conversations IA avec des personas uniques',
    description: "Dialoguez avec les meilleurs modèles d'IA via des personas historiques et uniques. Sécurisé et personnalisable. Commencez gratuitement.",
    keywords: 'IA, OpenRouter, Chatbot, Personas, GPT-4, Claude, Intelligence Artificielle, SaaS, Streaming',
};
</script>

<template>
    <Head>
        <title>PersonAI - Conversations IA avec des personas uniques</title>

        <title>{{ seo.title }}</title>
        <meta name="description" :content="seo.description" />
        <meta name="keywords" :content="seo.keywords" />
        <meta name="robots" content="index, follow" />
        <meta name="language" content="French" />
        <meta name="revisit-after" content="7 days" />
        <meta name="author" content="PersonAI" />
        <link rel="canonical" :href="appUrl" />
        <link rel="alternate" :href="appUrl" hreflang="fr" />
        <link rel="alternate" :href="appUrl" hreflang="x-default" />

        <meta property="og:type" content="website" />
        <meta property="og:url" :content="appUrl" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:image" :content="mainImage" />
        <meta property="og:site_name" :content="appName" />
        <meta property="og:locale" content="fr_FR" />

        <meta property="twitter:card" content="summary_large_image" />
        <meta property="twitter:site" content="@personai.app" />
        <meta property="twitter:url" :content="appUrl" />
        <meta property="twitter:title" :content="seo.title" />
        <meta property="twitter:description" :content="seo.description" />
        <meta property="twitter:image" :content="mainImage" />
    </Head>

    <div class="relative min-h-screen bg-black text-white">
        <!-- Grain texture overlay -->
        <div
            class="pointer-events-none fixed inset-0 opacity-[0.015]"
            style="
                background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E&quot;);
            "
        ></div>

        <!-- Header -->
        <header role="banner" class="fixed top-0 right-0 left-0 z-50 border-b border-white/5 bg-black/60 backdrop-blur-2xl">
            <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" role="navigation" aria-label="Main navigation">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex size-10 items-center justify-center rounded-lg border border-white/10 bg-white/5">
                            <Sparkles class="size-5 text-white" />
                        </div>
                        <span class="text-xl font-semibold tracking-tight text-white"> PeronAI </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="newMethod()"
                                class="rounded-lg bg-white px-6 py-2.5 text-sm font-medium text-black transition hover:bg-white/90 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                            >
                                Ouvrir l'App
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="login()"
                                class="rounded-lg px-4 py-2 text-sm font-medium text-gray-400 transition hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                            >
                                Se connecter
                            </Link>
                            <Link
                                :href="register()"
                                class="rounded-lg bg-white px-6 py-2.5 text-sm font-medium text-black transition hover:bg-white/90 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                            >
                                Commencer
                            </Link>
                        </template>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <main role="main">
            <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-28">
                <div class="absolute inset-0">
                    <div class="absolute top-1/3 left-1/2 h-[600px] w-[600px] -translate-x-1/2 rounded-full bg-white/3 blur-[120px]" />
                    <div class="absolute top-1/2 left-1/4 h-[400px] w-[400px] rounded-full bg-white/2 blur-[100px]" />
                    <div class="absolute right-1/4 bottom-1/3 h-[500px] w-[500px] rounded-full bg-white/2 blur-[100px]" />
                </div>

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="space-y-16 text-center">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm backdrop-blur-xl">
                            <span class="relative flex size-2">
                                <span class="absolute inline-flex size-full animate-ping rounded-full bg-white opacity-75" />
                                <span class="relative inline-flex size-2 rounded-full bg-white" />
                            </span>
                            <span class="text-gray-400">Propulsé par OpenRouter</span>
                        </div>

                        <h1 class="text-5xl leading-[1.1] font-bold tracking-tight sm:text-6xl lg:text-7xl">
                            L'IA conversationnelle
                            <br />
                            <span class="text-white"> réinventée </span>
                        </h1>

                        <p class="mx-auto max-w-2xl text-lg leading-relaxed text-gray-400 sm:text-xl">
                            Dialoguez avec les meilleurs modèles d'IA à travers des personas historiques et fictifs. Streaming temps réel, historique complet,
                            expérience premium.
                        </p>

                        <div class="flex flex-col items-center justify-center gap-4 pt-4 sm:flex-row">
                            <Link
                                :href="register()"
                                class="group inline-flex items-center gap-2 rounded-lg bg-white px-8 py-4 text-base font-medium text-black transition hover:bg-white/90 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                            >
                                <MessageSquare class="size-5" />
                                <span>Commencer gratuitement</span>
                            </Link>
                            <a
                                href="#features"
                                class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-8 py-4 text-base font-medium text-gray-300 backdrop-blur-xl transition hover:bg-white/10 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                            >
                                <span>Découvrir les fonctionnalités</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section id="features" class="relative py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 space-y-4 text-center">
                        <div class="inline-block rounded-lg border border-white/10 bg-white/5 px-4 py-1.5 text-sm font-medium text-gray-400 backdrop-blur-xl">
                            Fonctionnalités
                        </div>
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">
                            Tout ce dont vous avez besoin
                            <br />
                            <span class="text-white"> pour vos conversations IA </span>
                        </h2>
                        <p class="mx-auto max-w-2xl text-lg text-gray-400">Des fonctionnalités puissantes pour une expérience conversationnelle inégalée</p>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="(feature, index) in features"
                            :key="index"
                            class="group relative rounded-xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition hover:bg-white/[0.07]"
                        >
                            <div
                                class="flex size-12 items-center justify-center rounded-lg border border-white/10 bg-white/10 transition group-hover:bg-white/20"
                            >
                                <component :is="feature.icon" class="size-6 text-white" />
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-white">{{ feature.title }}</h3>
                            <p class="mt-3 leading-relaxed text-gray-400">
                                {{ feature.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section id="pricing" class="relative overflow-hidden py-20 sm:py-28">
                <div class="absolute top-1/2 left-1/2 h-[800px] w-[800px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/2 blur-[120px]" />

                <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 space-y-4 text-center">
                        <div class="inline-block rounded-lg border border-white/10 bg-white/5 px-4 py-1.5 text-sm font-medium text-gray-400 backdrop-blur-xl">
                            Tarifs
                        </div>
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">Un plan pour chaque besoin</h2>
                        <p class="mx-auto max-w-2xl text-lg text-gray-400">Choisissez l'offre qui correspond à votre utilisation</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <div
                            v-for="(plan, index) in plans"
                            :key="index"
                            :class="[
                                'relative rounded-xl p-8 backdrop-blur-xl transition',
                                plan.highlighted ? 'border-2 border-white/20 bg-white/10 lg:scale-105' : 'border border-white/10 bg-white/5',
                            ]"
                        >
                            <div
                                v-if="plan.highlighted"
                                class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-white px-4 py-1 text-xs font-medium text-black"
                            >
                                Le plus populaire
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-white">{{ plan.name }}</h3>
                                    <p class="mt-2 text-sm text-gray-400">{{ plan.description }}</p>
                                </div>

                                <div class="flex items-baseline gap-2">
                                    <span class="text-5xl font-bold text-white">{{ plan.price }}</span>
                                    <span class="text-gray-400">{{ plan.period }}</span>
                                </div>

                                <ul class="space-y-3">
                                    <li v-for="(feature, fIndex) in plan.features" :key="fIndex" class="flex items-start gap-3">
                                        <CheckCircle2 class="mt-0.5 size-5 shrink-0 text-gray-400" />
                                        <span class="text-gray-300">{{ feature }}</span>
                                    </li>
                                </ul>

                                <Link
                                    :href="register()"
                                    :class="[
                                        'block w-full rounded-lg px-6 py-3 text-center font-medium transition focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none',
                                        plan.highlighted
                                            ? 'bg-white text-black hover:bg-white/90'
                                            : 'border border-white/10 bg-white/5 text-gray-300 hover:bg-white/10',
                                    ]"
                                >
                                    {{ plan.cta }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section id="testimonials" class="py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 space-y-4 text-center">
                        <div class="inline-block rounded-lg border border-white/10 bg-white/5 px-4 py-1.5 text-sm font-medium text-gray-400 backdrop-blur-xl">
                            Témoignages
                        </div>
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">Ils nous font confiance</h2>
                        <p class="mx-auto max-w-2xl text-lg text-gray-400">Découvrez ce que nos utilisateurs disent de PersonAI</p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="(testimonial, index) in testimonials"
                            :key="index"
                            class="relative rounded-xl border border-white/10 bg-white/5 p-8 backdrop-blur-xl transition hover:bg-white/[0.07]"
                        >
                            <div class="mb-4 flex gap-1">
                                <Star v-for="star in testimonial.rating" :key="star" class="size-4 fill-gray-400 text-gray-400" />
                            </div>

                            <p class="mb-6 leading-relaxed text-gray-300">"{{ testimonial.content }}"</p>

                            <div class="flex items-center gap-3">
                                <div
                                    class="flex size-10 items-center justify-center rounded-full border border-white/10 bg-white/10 text-sm font-medium text-white"
                                >
                                    {{ testimonial.name.charAt(0) }}
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ testimonial.name }}</div>
                                    <div class="text-sm text-gray-400">{{ testimonial.role }} · {{ testimonial.company }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section id="faq" class="relative overflow-hidden py-20 sm:py-28">
                <div class="absolute top-1/2 left-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/2 blur-[120px]" />

                <div class="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 space-y-4 text-center">
                        <div class="inline-block rounded-lg border border-white/10 bg-white/5 px-4 py-1.5 text-sm font-medium text-gray-400 backdrop-blur-xl">
                            FAQ
                        </div>
                        <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">Questions fréquentes</h2>
                        <p class="text-lg text-gray-400">Tout ce que vous devez savoir sur PersonAI</p>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="(faq, index) in faqs"
                            :key="index"
                            class="overflow-hidden rounded-xl border border-white/10 bg-white/5 ring-white/50 backdrop-blur-xl transition focus-within:ring hover:bg-white/[0.07]"
                        >
                            <button @click="toggleFaq(index)" class="flex w-full items-center justify-between p-6 text-left transition outline-none">
                                <span class="pr-8 text-lg font-medium text-white">
                                    {{ faq.question }}
                                </span>
                                <ChevronDown :class="['size-5 shrink-0 text-gray-400 transition-transform', openFaq === index ? 'rotate-180' : '']" />
                            </button>
                            <div v-show="openFaq === index" class="px-6 pb-6 leading-relaxed text-gray-400">
                                {{ faq.answer }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-20 sm:py-28">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-12 backdrop-blur-xl sm:p-16">
                        <div class="absolute top-1/2 left-1/2 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/3 blur-[100px]" />

                        <div class="relative space-y-6 text-center">
                            <h2 class="text-4xl font-bold tracking-tight sm:text-5xl">Prêt à commencer ?</h2>
                            <p class="mx-auto max-w-2xl text-lg text-gray-400">
                                Rejoignez des milliers d'utilisateurs qui transforment leur façon de converser avec l'IA
                            </p>
                            <div class="flex flex-col items-center justify-center gap-4 pt-4 sm:flex-row">
                                <Link
                                    :href="register()"
                                    class="inline-flex items-center gap-2 rounded-lg bg-white px-8 py-4 text-base font-medium text-black transition hover:bg-white/90 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                                >
                                    <MessageSquare class="size-5" />
                                    <span>Commencer gratuitement</span>
                                </Link>
                                <a
                                    href="#pricing"
                                    class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-8 py-4 text-base font-medium text-gray-300 backdrop-blur-xl transition hover:bg-white/10 focus:ring-2 focus:ring-white focus:ring-offset-3 focus:ring-offset-black outline-none"
                                >
                                    <span>Voir les tarifs</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
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
                                class="flex size-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 ring-white/50 transition hover:bg-white/10 focus:ring-2 outline-none"
                            >
                                <Twitter class="size-4 text-gray-400" />
                            </a>
                            <a
                                href="https://github.com"
                                target="_blank"
                                aria-label="Visitez notre GitHub"
                                class="flex size-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 ring-white/50 transition hover:bg-white/10 focus:ring-2 outline-none"
                            >
                                <Github class="size-4 text-gray-400" />
                            </a>
                            <a
                                href="https://linkedin.com"
                                target="_blank"
                                aria-label="Rejoignez-nous sur LinkedIn"
                                class="flex size-9 items-center justify-center rounded-lg border border-white/10 bg-white/5 ring-white/50 transition hover:bg-white/10 focus:ring-2 outline-none"
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
                                    href="#features"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Fonctionnalités
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#pricing"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Tarifs
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#testimonials"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Témoignages
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#faq"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
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
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    À propos
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Blog
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Carrières
                                </a>
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
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
                                    href="/mentions-legales"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Mentions légales
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/mentions-legales#privacy"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Politique de confidentialité
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/mentions-legales#terms"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Conditions d'utilisation
                                </Link>
                            </li>
                            <li>
                                <Link
                                    href="/mentions-legales#cookies"
                                    class="rounded-xs text-gray-400 ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                                >
                                    Politique des cookies
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
                                href="mailto:contact@PersonAI.com"
                                class="rounded-xs ring-white ring-offset-4 ring-offset-black transition hover:text-white focus:ring-2 outline-none"
                            >
                                contact@PersonAI.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
