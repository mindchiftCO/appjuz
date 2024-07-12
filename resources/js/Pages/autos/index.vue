<template>
    <AppLayout title="Mis Autos">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Mis Autos
                </h2>
                <Link :href="route('autos.create')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Crear Auto
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="autos.data.length">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Archivo
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="auto in autos.data" :key="auto.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Link :href="route('autos.show', auto.id)"
                                            class="text-indigo-600 hover:text-indigo-900">
                                        {{ auto.nombre }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Link :href="/"
                                            class="inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M3 4a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V6a2 2 0 00-2-2H3zm1 2a1 1 0 011-1h10a1 1 0 011 1v1H4V6zm0 3h12v5a1 1 0 01-1 1H4a1 1 0 01-1-1v-5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ auto.archivo }}
                                        </Link>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('autos.edit', auto.id)"
                                            class="text-yellow-500 hover:text-yellow-700 font-bold py-1 px-3 rounded">
                                        Editar
                                        </Link>
                                        <button @click="deleteAuto(auto.id)"
                                            class="text-red-500 hover:text-red-700 font-bold py-1 px-3 rounded">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <p class="text-center text-gray-500">No tienes autos disponibles.</p>
                    </div>
                    <div class="mt-4">
                        <pagination :links="autos.links" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    autos: Object,
});

const deleteAuto = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este auto?')) {
        router.delete(route('autos.destroy', id));
    }
};
</script>
