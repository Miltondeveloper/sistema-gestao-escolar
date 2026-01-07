<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    permissions: Array,
    currentForbidden: Array,
});

const form = useForm({
    forbidden_permissions: props.currentForbidden || [],
});

const submit = () => {
    form.put(route('users.permissions.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Feedback visual opcional
        },
    });
};
</script>

<template>
    <Head title="Gerenciar Permissões" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Permissões: {{ user.name }}
            </h2>
        </template>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 m-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Ao marcar uma opção abaixo, você está <strong>BLOQUEANDO</strong> explicitamente o acesso, 
                            independente do Cargo (Role) do usuário.
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submit">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissão</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status do Cargo</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-red-600 uppercase tracking-wider">Ação de Bloqueio</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="permission in permissions" :key="permission.id" class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ permission.name }}</div>
                                <div class="text-xs text-gray-400">{{ permission.guard_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="permission.granted_by_role" 
                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Permitido
                                </span>
                                <span v-else class="text-gray-400 text-sm italic">
                                    Não possui
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           :value="permission.id" 
                                           v-model="form.forbidden_permissions" 
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-900">Bloquear</span>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="px-6 py-4 bg-gray-50 text-right border-t border-gray-200">
                    <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>