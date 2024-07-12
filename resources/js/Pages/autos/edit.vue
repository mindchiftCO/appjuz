<template>
    <AppLayout>
        <Head title="Editar Auto" />
        <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 w-full max-w-md mx-auto">
                <div class="mb-4">
                    <button @click="goBack" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver a inicio
                    </button>
                </div>
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Auto:</label>
                        <input type="text" v-model="form.nombre" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                    <div class="mb-4">
                        <p class="text-red-500 text-sm mb-2">Asegúrese de que las variables en el archivo docx estén escritas según el ejemplo: ${nombreDemandante}</p>
                        <label for="archivo" class="block text-sm font-medium text-gray-700">Archivo DOCX:</label>
                        <input type="file" @change="handleFileChange" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                    </div>
                    <div v-for="(variable, index) in form.variables" :key="index" class="mb-4 flex items-center">
                        <div class="flex-1">
                            <label :for="'variable-' + index" class="block text-sm font-medium text-gray-700">Variable {{ index + 1 }}:</label>
                            <input type="text" v-model="variable.nombre" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                        </div>
                        <button type="button" @click="removeVariable(index)" class="ml-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Borrar
                        </button>
                    </div>
                    <button type="button" @click="addVariable" class="mr-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Añadir Variable
                    </button>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

export default {
    props: {
        auto: Object,
    },
    setup(props) {
        const form = useForm({
            nombre: props.auto.nombre,
            archivo: null,
            variables: props.auto.variables || [{ nombre: '' }],
        });

        const handleFileChange = (event) => {
            form.archivo = event.target.files[0];
        };

        const addVariable = () => {
            form.variables.push({ nombre: '' });
        };

        const removeVariable = (index) => {
            form.variables.splice(index, 1);
        };

        const submit = () => {
            form.put(route('autos.update', props.auto.id));
        };

        const goBack = () => {
            router.visit(route('autos.index'));
        };

        return { form, handleFileChange, addVariable, removeVariable, submit, goBack };
    },
};
</script>
