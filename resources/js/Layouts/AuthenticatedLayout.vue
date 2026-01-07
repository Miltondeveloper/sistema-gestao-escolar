<script setup>
import { ref } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';

// Não precisamos mais de todo aquele código de navbar superior complexo
</script>

<template>
    <div class="flex h-screen bg-gray-100 overflow-hidden">
        
        <Sidebar />

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <header class="bg-white shadow-sm z-10" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>