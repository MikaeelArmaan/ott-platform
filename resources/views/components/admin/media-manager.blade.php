@props([
'name',
'value' => null,
'folder' => 'uploads',
'accept' => '*',
'multiple' => false
])

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div
    x-data="mediaManager('{{ $folder }}', @json($value), {{ json_encode($multiple) }})"
    x-init="typeof init === 'function' && init()"
    class="space-y-3">

    {{-- PREVIEW (SINGLE ONLY) --}}
    <template x-if="!multiple && selected">
        <div class="bg-zinc-800 rounded p-3 text-center">

            <template x-if="selected.type === 'image'">
                <img :src="selected.url" class="h-32 mx-auto rounded object-cover">
            </template>

            <template x-if="selected.type === 'video'">
                <video :src="selected.url" class="h-32 mx-auto rounded" controls></video>
            </template>

        </div>
    </template>

    {{-- BUTTON --}}
    <button
        type="button"
        @click="open = true; loadFiles()"
        class="bg-zinc-700 hover:bg-zinc-600 px-4 py-2 rounded text-sm">
        Upload / Select Media
    </button>

    {{-- HIDDEN INPUTS --}}
    <!-- MULTIPLE -->
    <template x-if="multiple">
        <template x-for="file in selected" :key="file.path">
            <input type="hidden" name="{{ $name }}" :value="file.path">
        </template>
    </template>

    <!-- SINGLE -->
    <template x-if="!multiple">
        <input type="hidden" name="{{ $name }}" :value="selected?.path">
    </template>

    {{-- MODAL --}}
    <div
        x-show="open"
        x-cloak
        style="display:none;"
        @click.self="open = false"
        class="fixed inset-0 bg-black/80 flex items-center justify-center z-50">

        <div class="bg-zinc-900 w-full max-w-5xl p-4 rounded-lg">

            {{-- HEADER --}}
            <div class="flex justify-between mb-3">
                <h2 class="text-lg font-semibold">Media Manager</h2>

                <button
                    type="button"
                    @click="open = false"
                    class="bg-red-600 px-3 py-1 rounded text-white">
                    ✕
                </button>
            </div>

            {{-- TABS --}}
            <div class="flex gap-4 mb-4">
                <button type="button" @click="tab='upload'" :class="tabClass('upload')">
                    Upload
                </button>
                <button type="button" @click="tab='library'" :class="tabClass('library')">
                    Library
                </button>
            </div>

            {{-- UPLOAD TAB --}}
            <div x-show="tab === 'upload'" x-transition>

                <div
                    @click="$refs.input.click()"
                    @dragover.prevent
                    @drop.prevent="handleDrop($event)"
                    class="border-2 border-dashed border-zinc-600 rounded-lg p-6 text-center cursor-pointer hover:border-red-500">

                    <p class="text-sm text-gray-400">
                        Click or Drag file here
                    </p>

                </div>

                <input
                    type="file"
                    x-ref="input"
                    class="hidden"
                    accept="{{ $accept }}"
                    {{ $multiple ? 'multiple' : '' }}
                    @change="uploadFiles($event.target.files)">

            </div>

            {{-- LIBRARY TAB --}}
            <div x-show="tab === 'library'" x-transition>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 max-h-[400px] overflow-y-auto">

                    <template x-for="file in files" :key="file.path">

                        <div
                            @click="select(file)"
                            class="cursor-pointer border border-zinc-700 rounded p-2 hover:border-red-500">

                            <template x-if="file.type === 'image'">
                                <img :src="file.url" class="h-24 w-full object-cover rounded">
                            </template>

                            <template x-if="file.type === 'video'">
                                <video :src="file.url" class="h-24 w-full object-cover rounded"></video>
                            </template>

                            <div class="text-xs mt-1 truncate" x-text="file.name"></div>

                        </div>

                    </template>

                </div>

            </div>

        </div>

    </div>

</div>