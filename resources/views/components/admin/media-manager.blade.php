@props([
'name' => null,
'value' => null,
'folder' => 'uploads',
'accept' => '*',
'multiple' => false,
'target' => null
])
<div
    {{ $attributes }} 
    x-data="mediaManager({
        folder: '{{ $folder }}',
        value: @js($value),
        multiple: {{ $multiple ? 'true' : 'false' }},
        target: null
    })"
    x-init="init()"
    class="space-y-3">
    {{-- SINGLE PREVIEW --}}
    <template x-if="!multiple && selected">
        <div class="bg-zinc-800 rounded p-3 text-center">
            <template x-if="selected.type === 'image'">
                <img :src="selected.url" class=" h-32 mx-auto rounded object-cover">
            </template>

            <template x-if="selected.type === 'video'">
                <video :src="selected.url" class=" h-32 mx-auto rounded" controls></video>
            </template>

            <div class="mt-3">
                <button
                    type="button"
                    @click="remove()"
                    class="bg-red-600 hover:bg-red-500 px-3 py-1 rounded text-sm text-white">
                    Remove
                </button>
            </div>
        </div>
    </template>

    {{-- MULTIPLE SELECTED PREVIEW --}}
    <template x-if="multiple && selected.length">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <template x-for="file in selected" :key="file.path">
                <div class="bg-zinc-800 rounded p-2 relative">
                    <template x-if="file.type === 'image'">
                        <img :src="file.url" class="h-24 w-full rounded object-cover">
                    </template>

                    <template x-if="file.type === 'video'">
                        <video :src="file.url" class="h-24 w-full rounded object-cover"></video>
                    </template>

                    <button
                        type="button"
                        @click="remove(file)"
                        class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                        ✕
                    </button>
                </div>
            </template>
        </div>
    </template>

    {{-- OPEN BUTTON --}}
    <button
        type="button"
        @click="open = true; loadFiles()"
        class="bg-zinc-700 hover:bg-zinc-600 px-4 py-2 rounded text-sm">
        Upload / Select Media
    </button>

    {{-- HIDDEN INPUTS --}}
    <!-- <template x-if="multiple">
        <template x-for="file in selected" :key="file.path">
            <input type="hidden" name="{{ $name }}[]" :value="file.path">
        </template>
    </template> -->

    @if($name)
    <template x-if="multiple">
        <template x-for="file in selected" :key="file.path">
            <input type="hidden" name="{{ $name }}[]" :value="file.path">
        </template>
    </template>

    <template x-if="!multiple">
        <input type="hidden" name="{{ $name }}" :value="selected ? selected.path : ''">
    </template>
    @endif

    {{-- MODAL --}}
    <div
        x-show="open"
        x-cloak
        @click.self="!uploading && (open = false)"
        class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
        <div class="bg-zinc-900 w-full max-w-5xl p-4 rounded-lg max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold">Media Manager</h2>
                <button
                    type="button"
                    @click="!uploading && (open = false)"
                    :class="uploading ? 'opacity-50 cursor-not-allowed' : ''"
                    class="bg-red-600 px-3 py-1 rounded text-white">
                    ✕
                </button>
            </div>

            <div class="flex gap-4 mb-4 border-b border-zinc-700 pb-2">
                <button
                    type="button"
                    @click="!uploading && (tab='upload')"
                    :class="tabClass('upload') + (uploading ? ' opacity-50 pointer-events-none' : '')">
                    Upload
                </button>

                <button
                    type="button"
                    @click="!uploading && (tab='library')"
                    :class="tabClass('library') + (uploading ? ' opacity-50 pointer-events-none' : '')">
                    Library
                </button>
            </div>

            {{-- UPLOAD TAB --}}
            <div x-show="tab === 'upload'" class="relative">

                <!-- LOADER OVERLAY -->
                <div x-show="uploading"
                    x-cloak
                    class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center z-20 rounded">

                    <!-- Spinner -->
                    <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>

                    <!-- Progress -->
                    <p class="text-sm text-white mt-3">
                        Uploading... <span x-text="progress"></span>%
                    </p>
                </div>

                <!-- DROPZONE -->
                <div
                    @click="!uploading && $refs.input.click()"
                    @dragover.prevent
                    @drop.prevent="!uploading && uploadFiles($event.dataTransfer.files)"
                    :class="uploading ? 'opacity-50 pointer-events-none' : ''"
                    class="border-2 border-dashed border-zinc-600 rounded-lg p-6 text-center cursor-pointer hover:border-red-500 transition">

                    <span x-show="!uploading">Drag files here or click to upload</span>
                    <span x-show="uploading">
                        Uploading <span x-text="progress"></span>%...
                    </span>
                </div>

                <!-- INPUT -->
                <input
                    type="file"
                    x-ref="input"
                    class="hidden"
                    accept="{{ $accept }}"
                    {{ $multiple ? 'multiple' : '' }}
                    @change="uploadFiles($event.target.files)">
            </div>

            {{-- LIBRARY TAB --}}
            <div x-show="tab === 'library'">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <template x-for="file in files" :key="file.path">
                        <div class="border border-zinc-700 rounded p-2 bg-zinc-800">
                            <div @click="select(file)" class="cursor-pointer">
                                <template x-if="file.type === 'image'">
                                    <img :src="file.url" class="h-24 w-full object-cover rounded">
                                </template>

                                <template x-if="file.type === 'video'">
                                    <video :src="file.url" class="h-24 w-full object-cover rounded"></video>
                                </template>

                                <div class="mt-2 text-xs text-gray-300 truncate" x-text="file.name"></div>
                            </div>

                            <div class="mt-2 flex justify-between gap-2">
                                <button
                                    type="button"
                                    @click="select(file)"
                                    class="flex-1 bg-zinc-700 hover:bg-zinc-600 text-xs px-2 py-1 rounded">
                                    Select
                                </button>

                                <button
                                    type="button"
                                    @click="deleteFile(file)"
                                    class="bg-red-600 hover:bg-red-500 text-xs px-2 py-1 rounded text-white">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>