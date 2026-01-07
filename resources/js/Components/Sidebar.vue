<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    user: Object
});
</script>

<template>
    <aside class="w-64 bg-gray-900 text-white flex flex-col min-h-screen transition-all duration-300">
        <div class="h-16 flex items-center justify-center bg-gray-800 shadow-md">
            <Link :href="route('dashboard')" class="text-xl font-bold tracking-wider text-indigo-400">
                FUTURA
            </Link>
        </div>

        <div class="p-4 border-b border-gray-700">
            <p class="text-sm text-gray-400">Logado como:</p>
            <p class="font-medium truncate">{{ $page.props.auth.user.name }}</p>
        </div>

        <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
            
            <Link :href="route('dashboard')" 
                  :class="route().current('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                  class="group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </Link>

            <Link href="/users/3/permissions" 
                  :class="$page.url.includes('/permissions') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'"
                  class="group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Permissões (Teste)
            </Link>

        </nav>

        <div class="p-4 bg-gray-900 border-t border-gray-800">
            <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center text-sm text-red-400 hover:text-red-300">
                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sair do Sistema
            </Link>
        </div>
    </aside>
</template>