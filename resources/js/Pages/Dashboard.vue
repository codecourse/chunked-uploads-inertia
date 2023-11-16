<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { reactive, ref, computed } from 'vue'
import { createUpload } from '@mux/upchunk'

defineProps({
    files: Array
})

const file = ref(null)

const initialState = {
    file: null,
    uploader: null,
    progress: 0,
    uploading: false,
}

const state = reactive({
    ...initialState,
    formattedProgress: computed(() => Math.round(state.progress)),
    reset: () => {
        Object.assign(state, initialState)
    }
})

const cancel = () => {
    state.uploader.abort()
    state.reset()
}

const submit = () => {
    state.file = file.value.files[0]

    if (!state.file) {
        return
    }

    state.uploader = createUpload({
        endpoint: route('files.store'),
        headers: {
            'X-CSRF-TOKEN': usePage().props.csrf_token
        },
        method: 'post',
        file: state.file,
        chunkSize: 10 * 1024 // 10mb
    })

    state.uploader.on('attempt', () => {
        state.uploading = true
    })

    state.uploader.on('progress', (p) => {
        state.progress = p.detail
    })

    state.uploader.on('success', () => {
        state.reset()

        router.reload({
            only: ['files'],
            preserveScroll: true
        })
    })
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
                    <form class="p-6 text-gray-900 space-y-6" v-on:submit.prevent="submit">
                        <div class="flex items-center">
                            <input type="file" name="file" ref="file" class="flex-grow">
                            <PrimaryButton>
                                Upload
                            </PrimaryButton>
                        </div>

                        <div v-if="state.uploading" class="space-y-2">
                            <div class="bg-gray-100 shadow-inner h-3 rounded overflow-hidden">
                                <div class="bg-blue-500 h-full transition-all duration-200" v-bind:style="{ width: state.progress + '%' }"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <button class="text-sm text-indigo-500" v-if="!state.uploader.paused" v-on:click="state.uploader.pause()">Pause</button>
                                    <button class="text-sm text-indigo-500" v-if="state.uploader.paused" v-on:click="state.uploader.resume()">Resume</button>

                                    <button class="text-sm text-indigo-500" v-on:click="cancel">Cancel</button>
                                </div>
                                <div class="text-sm">
                                    {{ state.formattedProgress }}%
                                </div>
                            </div>
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
