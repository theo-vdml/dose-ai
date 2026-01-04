<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';

    const props = defineProps<{
        conversations: any[]
    }>()

    console.log(props.conversations);

</script>

<template>

    <AppLayout>

        <ul>
            <li v-for="conversation in conversations" :key="conversation.id">{{ conversation.title }}</li>
        </ul>

    </AppLayout>

</template>
