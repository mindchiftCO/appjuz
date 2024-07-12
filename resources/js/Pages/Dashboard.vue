<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    autos: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const hasAutos = computed(() => props.autos.data.length > 0);

const searchAutos = () => {
    router.get(route('dashboard'), { search: search.value }, { preserveState: true, preserveScroll: true });
};
</script>

<template>
    <AppLayout title="Inicio">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Formatos Auto
                </h2>
                <Link :href="route('autos.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Crear Auto
                </Link>
            </div>
            <div class="mt-4">
                <input v-model="search" @input="searchAutos" type="text" placeholder="Buscar autos..." class="border-2 border-gray-300 p-2 rounded w-full" />
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="hasAutos" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-24">
                        <div v-for="auto in autos.data" :key="auto.id" class="bg-gray-100 p-4 rounded-lg hover:bg-gray-200 transition-colors cursor-pointer">
                            <Link :href="route('autos.show', auto.id)" class="block h-full">
                                <div class="text-center">
                                    <div class="flex justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 2H10a2 2 0 0 0-2 2v4H4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM8 4h2v4H8V4zm8 14H4v-8h10v4h4v4zm2-6h-6V4h6v8z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold mb-2">{{ auto.nombre }}</h3>
                                </div>
                            </Link>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-center text-gray-500">No hay autos disponibles.</p>
                    </div>
                </div>
                <div class="mt-4">
                    <ul class="flex justify-center space-x-2">
                        <li v-for="link in autos.links" :key="link.label" :class="{ 'font-bold': link.active }">
                            <button @click="searchAutos(link.url)" :disabled="!link.url" v-html="link.label" class="px-3 py-1 border rounded"></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

