<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, ref } from 'vue'

defineProps({
    files: Array
})

const file = ref(null)

const state = reactive({
    file: null
})

const submit = () => {
    state.file = file.value.files[0]

    if (!state.file) {
        return
    }

    console.log('start upload')
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form class="p-6 text-gray-900" v-on:submit.prevent="submit">
                        <div class="flex items-center">
                            <input type="file" name="file" ref="file" class="flex-grow">
                            <PrimaryButton>
                                Upload
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

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
