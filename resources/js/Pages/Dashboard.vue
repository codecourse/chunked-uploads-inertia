<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    files: Array
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <template v-if="files.length">
                            <div v-for="file in files" :key="file.id">
                                {{ file.file_path }}
                            </div>
                        </template>
                        <template v-else>
                            No files yet.
                        </template>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
