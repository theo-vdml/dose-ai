<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Sparkles } from 'lucide-vue-next';

interface TocItem {
    id: string;
    title: string;
    level: number;
}

defineProps<{
    title: string;
    description: string;
    updated_at: string;
    content: string;
    toc: TocItem[];
}>();

const scrollTo = (id: string) => {
    const element = document.getElementById(id);
    if (element) {
        const offset = 100;
        const bodyRect = document.body.getBoundingClientRect().top;
        const elementRect = element.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth',
        });

        history.pushState(null, '', `#${id}`);
    }
};
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen bg-black font-sans text-white selection:bg-white selection:text-black">
        <div class="pointer-events-none fixed top-0 left-1/2 z-0 h-[400px] w-[800px] -translate-x-1/2 rounded-full bg-white/5 blur-[120px]"></div>

        <header class="sticky top-0 z-20 border-b border-white/10 bg-black/90 backdrop-blur-xl transition-colors duration-300">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
                <div class="flex items-center gap-3">
                    <div class="flex size-10 items-center justify-center rounded-lg border border-white/10 bg-white/5">
                        <Sparkles class="size-5 text-white" />
                    </div>
                    <span class="text-xl font-semibold tracking-tight text-white"> PersonAI </span>
                </div>

                <Link href="/" class="text-sm font-medium text-gray-400 transition-colors hover:text-white"> ← Retour à l'accueil </Link>
            </div>
        </header>

        <div class="relative z-10 mx-auto max-w-7xl px-6 py-12 lg:py-20">
            <div class="mb-12 text-center lg:text-left">
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-white md:text-5xl">{{ title }}</h1>
                <p class="max-w-2xl text-lg text-gray-400">
                    {{ description }}
                </p>
            </div>

            <div class="lg:grid lg:grid-cols-12 lg:gap-16">
                <div class="mb-12 lg:hidden">
                    <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                        <h2 class="mb-4 text-sm font-semibold tracking-widest text-gray-300 uppercase">Sommaire</h2>
                        <nav>
                            <ul class="space-y-3">
                                <li v-for="item in toc" :key="item.id">
                                    <a
                                        :href="`#${item.id}`"
                                        @click.prevent="scrollTo(item.id)"
                                        :class="[
                                            'block text-sm transition-colors',
                                            'text-gray-500 hover:text-gray-300',
                                            item.level === 3 ? 'pl-4' : '',
                                            item.level === 4 ? 'pl-8' : '',
                                        ]"
                                    >
                                        {{ item.title }}
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <aside class="hidden lg:col-span-3 lg:block">
                    <nav class="sticky top-28">
                        <h2 class="mb-6 text-xs font-semibold tracking-widest text-gray-500 uppercase">Sur cette page</h2>
                        <ul class="space-y-4 border-l border-white/10">
                            <li v-for="item in toc" :key="item.id" class="-ml-px">
                                <a
                                    :href="`#${item.id}`"
                                    @click.prevent="scrollTo(item.id)"
                                    :class="[
                                        'block border-l-2 pl-4 text-sm transition-all duration-300',
                                        'border-transparent text-gray-500 hover:border-gray-700 hover:text-gray-300',
                                        item.level === 3 ? 'ml-4 text-xs' : item.level === 4 ? 'ml-8 text-xs' : '',
                                    ]"
                                >
                                    {{ item.title }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </aside>

                <main class="lg:col-span-9">
                    <div class="relative rounded-2xl border border-white/10 bg-white/2 p-6 shadow-2xl md:p-12">
                        <div
                            class="prose prose-lg max-w-none prose-invert prose-headings:font-bold prose-headings:tracking-tight prose-h2:mt-12 prose-h2:mb-6 prose-h2:border-b prose-h2:border-white/10 prose-h2:pb-4 prose-h2:text-2xl prose-h2:text-white prose-h3:mt-8 prose-h3:text-xl prose-h3:text-gray-200 prose-p:leading-relaxed prose-p:text-gray-400 prose-a:text-white prose-a:underline prose-a:decoration-gray-600 prose-a:underline-offset-4 hover:prose-a:decoration-white prose-strong:text-white prose-li:text-gray-400 prose-table:border-collapse prose-th:border-b prose-th:border-white/20 prose-th:text-white prose-td:border-b prose-td:border-white/10 prose-td:py-4 [&>h2:first-of-type]:mt-0!"
                            v-html="content"
                        ></div>
                    </div>

                    <div class="mt-8 text-center text-sm text-gray-600 lg:text-right">Dernière mise à jour : {{ updated_at }}</div>
                </main>
            </div>
        </div>

        <Footer />
    </div>
</template>
