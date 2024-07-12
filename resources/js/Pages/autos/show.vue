<template>
  <AppLayout title="Detalles del Auto">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Generar {{ auto.nombre }} :
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex flex-wrap">
            <div class="w-full lg:w-1/2 mb-6 lg:mb-0">
              <h3 class="text-lg font-semibold mb-4">Detalles del Documento</h3>
              <div class="border-2 border-gray-300 rounded p-4 text-center">
                <svg class="w-12 h-12 text-blue-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M13 2H6C4.897 2 4 2.897 4 4V20C4 21.103 4.897 22 6 22H18C19.103 22 20 21.103 20 20V8L13 2zM12 11C12.553 11 13 11.447 13 12V15C13 15.553 12.553 16 12 16C11.447 16 11 15.553 11 15V12C11 11.447 11.447 11 12 11zM12 18C11.447 18 11 17.553 11 17C11 16.447 11.447 16 12 16C12.553 16 13 16.447 13 17C13 17.553 12.553 18 12 18z" />
                </svg>
              </div>
            </div>
            <div class="w-full lg:w-1/2 lg:pl-6">
              <h3 class="text-lg font-semibold mb-4">Ingresar datos:</h3>
              <form @submit.prevent="submit">
                <div v-for="variable in editableVariables" :key="variable.id" class="mb-4">
                  <label :for="variable.nombre" class="block text-gray-700">{{ variable.nombre }}</label>
                  <input v-model="variable.valor" :id="variable.nombre"
                    class="mt-1 block w-full border-2 border-gray-300 rounded py-2 px-3" type="text">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  Generar Word
                </button>
              </form>
              <a ref="downloadLink" style="display:none"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';

const { props } = usePage();
const { auto, variables } = props;

const editableVariables = reactive(variables.map(variable => ({
  ...variable,
  valor: variable.valor || ''
})));

const downloadLink = ref(null);

const submit = async () => {
  const data = editableVariables.reduce((acc, variable) => {
    acc[variable.nombre] = variable.valor;
    return acc;
  }, {});

  try {
    const response = await fetch(route('autos.generarWord', { auto: auto.id }), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(data)
    });

    if (response.ok) {
      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const link = downloadLink.value;
      link.href = url;
      link.download = response.headers.get('Content-Disposition').split('filename=')[1];
      link.click();
      window.URL.revokeObjectURL(url);
    } else {
      console.error('Error al generar el archivo:', await response.json());
    }
  } catch (error) {
    console.error('Error en la solicitud de generación de archivo:', error);
  }
};
</script>

