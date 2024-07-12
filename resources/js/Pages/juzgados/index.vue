<template>
    <AppLayout>

        <Head title="Juzgados" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left Column: List of Records -->
                        <div class="w-full md:w-3/4 pr-4">
                            <h2 class="text-2xl mb-4">Gestionar Juzgados</h2>
                            <div class="mb-4">
                                <!-- Alerta de éxito -->
                                <div v-if="successMessage"
                                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <strong class="font-bold">Éxito!</strong>
                                    <span class="block sm:inline">{{ successMessage }}</span>
                                </div>

                                <!-- Alerta de error -->
                                <div v-if="errorMessage"
                                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <strong class="font-bold">Error!</strong>
                                    <span class="block sm:inline">{{ errorMessage }}</span>
                                </div>
                            </div>

                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="w-1/3 px-4 py-2">Nombre</th>
                                        <th class="w-1/3 px-4 py-2">Descripción</th>
                                        <th class="w-1/3 px-4 py-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="juzgado in juzgados.data" :key="juzgado.id">
                                        <td class="border px-4 py-2">{{ juzgado.nombre }}</td>
                                        <td class="border px-4 py-2">{{ juzgado.descripcion }}</td>
                                        <td class="border px-4 py-2">
                                            <button @click="editJuzgado(juzgado, 'Juzgado editado correctamente.')"
                                                class="bg-blue-500 text-white px-2 py-1 rounded">
                                                Editar
                                            </button>
                                            <button
                                                @click="deleteJuzgado(juzgado.id, 'Juzgado eliminado correctamente.')"
                                                class="bg-red-500 text-white px-2 py-1 rounded ml-2">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <div class="flex justify-end">
                                                <JetstreamPaginationLinks :paginator="$page.props.juzgados" />
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- Right Column: Form -->
                        <div class="w-full md:w-1/4 pl-4 mt-4 md:mt-0">
                            <h2 class="text-2xl mb-4">{{ formTitle }}</h2>
                            <form @submit.prevent="submitForm">
                                <div class="mb-4">
                                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                    <input id="nombre" v-model="form.nombre"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <div v-if="errors.nombre" class="text-red-500 text-sm mt-2">
                                        {{ errors.nombre }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="descripcion"
                                        class="block text-sm font-medium text-gray-700">Descripción</label>
                                    <textarea id="descripcion" v-model="form.descripcion"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    <div v-if="errors.descripcion" class="text-red-500 text-sm mt-2">
                                        {{ errors.descripcion }}
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
                                        Guardar
                                    </button>
                                    <button type="button" @click="resetForm"
                                        class="ml-2 bg-gray-300 text-black px-4 py-2 rounded">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import axios from 'axios';

export default {
    components: {
        AppLayout,
    },
    props: {
        juzgados: Object,
        filters: Object,
        errors: Object,
    },
    setup(props) {
        const form = ref({
            id: null,
            nombre: '',
            descripcion: '',
        });
        const successMessage = ref('');
        const errorMessage = ref('');
        const formTitle = ref('Crear Juzgado');

        const resetForm = () => {
            form.value = {
                id: null,
                nombre: '',
                descripcion: '',
            };
            formTitle.value = 'Crear Juzgado';
            props.errors = {};
        };

        const submitForm = async () => {
            let url = '/juzgados';
            let method = 'post';
            if (form.value.id) {
                url = `/juzgados/${form.value.id}`;
                method = 'post';
            }

            try {
                await axios[method](url, form.value);
                successMessage.value = 'Juzgado guardado correctamente.';
                setTimeout(() => { successMessage.value = ''; }, 3000);
                window.location.reload();
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    props.errors = error.response.data.errors;
                }
            }
        };

        const editJuzgado = (juzgado, successMsg) => {
            form.value = { ...juzgado };
            formTitle.value = 'Editar Juzgado';
            successMessage.value = successMsg || '';
            errorMessage.value = '';
        };

        const deleteJuzgado = async (id, successMsg) => {
            try {
                await axios.delete(`/juzgados/${id}`);
                successMessage.value = successMsg || '';
                errorMessage.value = '';
                setTimeout(() => {
                    successMessage.value = '';
                    window.location.reload(); 
                }, 1000); 
            } catch (error) {
                console.error(error);
            }
        };


        return {
            form,
            formTitle,
            resetForm,
            submitForm,
            editJuzgado,
            deleteJuzgado,
            successMessage,
            errorMessage,
        };
    },
};
</script>
