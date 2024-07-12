<template>
    <div class="min-h-screen flex flex-col sm:flex-row sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="flex-shrink-0">
            <slot name="logo" />
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg sm:ml-6">
            <slot />
        </div>
    </div>
</template>
