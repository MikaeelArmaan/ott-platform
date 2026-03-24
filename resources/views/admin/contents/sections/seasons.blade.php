<x-admin.card>

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-white">Seasons & Episodes</h2>

        <button type="button"
            @click="addSeason"
            class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-sm">
            + Add Season
        </button>
    </div>

    <!-- IMPORTANT -->
    <input type="hidden" name="seasons" :value="cleanSeasons()">
    
    <!-- SEASONS -->
    <div class="space-y-8"
        x-init="initSeasonSortable($el)">

        <template x-for="(season, sIndex) in seasons" :key="season.id || sIndex">

            <div class="bg-zinc-900 border border-zinc-700 rounded-xl p-5">

                <!-- Hidden -->
                <input type="hidden" x-model="season.id">
                <input type="hidden" x-model="season.season_number">

                <!-- SEASON HEADER -->
                <div class="flex justify-between items-center mb-4">

                    <div class="flex items-center gap-3 w-full">

                        <span class="drag-handle cursor-move text-gray-500">☰</span>

                        <span class="text-gray-400 text-sm">
                            Season <span x-text="sIndex + 1"></span>
                        </span>

                        <input type="text"
                            x-model="season.name"
                            placeholder="Season Title"
                            class="bg-zinc-800 px-3 py-2 rounded text-white w-full max-w-xs">

                    </div>

                    <button type="button"
                        @click="removeSeason(sIndex)"
                        class="text-red-500 text-sm">
                        Delete
                    </button>

                </div>

                <!-- EPISODES -->
                <div class="space-y-4"
                    x-init="initEpisodeSortable($el, sIndex)">

                    <template x-for="(ep, eIndex) in season.episodes" :key="ep.id || eIndex">

                        <div class="bg-black/40 border border-zinc-800 rounded-lg p-4">

                            <!-- Hidden -->
                            <input type="hidden" x-model="ep.id">
                            <input type="hidden" x-model="ep.episode_number">

                            <!-- HEADER -->
                            <div class="flex justify-between items-center mb-3 cursor-pointer"
                                @click="ep.open = !ep.open">

                                <div class="flex items-center gap-2">

                                    <span class="drag-handle cursor-move text-gray-500 text-xs">☰</span>

                                    <span class="text-sm text-gray-400">
                                        Episode <span x-text="eIndex + 1"></span>
                                    </span>

                                    <span class="text-white text-sm font-medium"
                                        x-text="ep.title || 'Untitled'">
                                    </span>
                                </div>

                                <div class="flex items-center gap-3">

                                    <!-- Preview -->
                                    <button type="button"
                                        @click.stop="ep.preview = !ep.preview"
                                        class="text-xs text-blue-400">
                                        Preview
                                    </button>

                                    <!-- Toggle -->
                                    <span class="text-xs text-gray-500"
                                        x-text="ep.open ? '▲' : '▼'"></span>
                                </div>

                            </div>

                            <!-- COLLAPSIBLE BODY -->
                            <div x-show="ep.open" x-transition class="space-y-4">

                                <!-- GRID -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <!-- TITLE -->
                                    <input type="text"
                                        x-model="ep.title"
                                        placeholder="Episode Title"
                                        class="bg-zinc-800 px-3 py-2 rounded text-white">

                                    <!-- DURATION -->
                                    <input type="number"
                                        x-model="ep.runtime"
                                        placeholder="Duration (seconds)"
                                        class="bg-zinc-800 px-3 py-2 rounded text-white">

                                </div>

                                <!-- DESCRIPTION -->
                                <textarea
                                    x-model="ep.description"
                                    rows="2"
                                    placeholder="Episode description..."
                                    class="w-full bg-zinc-800 px-3 py-2 rounded text-white resize-none">
                                </textarea>

                                <!-- MEDIA -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <!-- THUMBNAIL -->
                                    <div>
                                        <label class="text-xs text-gray-400 mb-1 block">Thumbnail</label>

                                        <input type="file"
                                            :name="`episode_thumbnails[${sIndex}][${eIndex}]`"
                                            accept="image/*"
                                            class="text-xs text-gray-400"
                                            @change="previewImage($event, ep)">

                                        <!-- NEW IMAGE PREVIEW -->
                                        <template x-if="ep.thumbnail_preview">
                                            <img :src="ep.thumbnail_preview"
                                                class="mt-2 h-24 rounded border border-zinc-700 object-cover">
                                        </template>

                                        <!-- EXISTING IMAGE -->
                                        <template x-if="!ep.thumbnail_preview && ep.thumbnail">
                                            <img :src="'/storage/' + ep.thumbnail"
                                                class="mt-2 h-24 rounded border border-zinc-700 object-cover">
                                        </template>
                                    </div>

                                    <!-- VIDEO -->
                                    <div>
                                        <label class="text-xs text-gray-400 mb-1 block">Video</label>

                                        <input type="file"
                                            :name="`episode_videos[${sIndex}][${eIndex}]`"
                                            accept="video/mp4"
                                            class="text-xs text-gray-400"
                                            @change="previewVideo($event, ep)">

                                        <!-- STATUS -->
                                        <template x-if="ep.video">
                                            <div class="text-xs mt-1">
                                                <span class="text-green-400" x-show="ep.is_processed">
                                                    ✔ Ready
                                                </span>
                                                <span class="text-yellow-400" x-show="!ep.is_processed">
                                                    ⏳ Processing
                                                </span>
                                            </div>
                                        </template>
                                    </div>

                                </div>
                                <!-- VIDEO PREVIEW -->
                                <template x-if="ep.video_preview || ep.video">
                                    <div x-show="ep.preview" x-transition>

                                        <video controls class="w-full rounded-lg mt-3 border border-zinc-700">

                                            <!-- NEW FILE -->
                                            <template x-if="ep.video_preview">
                                                <source :src="ep.video_preview" type="video/mp4">
                                            </template>

                                            <!-- EXISTING FILE -->
                                            <template x-if="!ep.video_preview && ep.video">
                                                <source :src="'/storage/' + ep.video" type="video/mp4">
                                            </template>

                                        </video>

                                    </div>
                                </template>

                                <!-- REMOVE -->
                                <button type="button"
                                    @click="removeEpisode(sIndex, eIndex)"
                                    class="text-red-400 text-xs">
                                    Remove Episode
                                </button>

                            </div>

                        </div>

                    </template>

                    <!-- ADD EPISODE -->
                    <button type="button"
                        @click="addEpisode(sIndex)"
                        class="text-sm text-red-500">
                        + Add Episode
                    </button>

                </div>

            </div>

        </template>

    </div>

</x-admin.card>