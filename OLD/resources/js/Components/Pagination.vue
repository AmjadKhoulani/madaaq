<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap border-2 border-black/5 bg-black/[0.02] p-1.5 rounded-2xl w-fit">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="link.url === null" 
                     class="mr-1 mb-1 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-[#86868b] leading-4 border border-transparent rounded-xl" 
                     v-html="link.label" />
                <Link v-else 
                      class="mr-1 mb-1 px-4 py-2 text-[10px] font-black uppercase tracking-widest leading-4 border rounded-xl transition-all" 
                      :class="{ 'bg-white text-black shadow-lg border-black/5': link.active, 'text-[#86868b] hover:bg-white/50 border-transparent': !link.active }" 
                      :href="link.url" 
                      v-html="link.label" />
            </template>
        </div>
    </div>
</template>
