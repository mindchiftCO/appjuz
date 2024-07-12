<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Bienvenido" />
    <div class="bg-gradient-to-r from-yellow-400 via-blue-500 to-red-600 text-black/50 dark:bg-black dark:text-white/50 min-h-screen flex flex-col items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg w-full text-center">
            <img class="w-32 h-32 mx-auto" src="/images/logoConsejo.svg" alt="Logo" />
            <h1 class="font-semibold text-xl text-gray-800 leading-tight mb-4">GESTOR DE FORMATOS AUTOS</h1>
            <div class="space-y-4">
                <Link v-if="canLogin" :href="route('login')" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Iniciar sesión
                </Link>
                <Link v-if="canRegister" :href="route('register')" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">
                    Registrarse
                </Link>
            </div>
        </div>
    </div>
</template>

