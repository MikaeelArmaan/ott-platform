<x-admin.card>

    <h2 class="text-lg font-semibold mb-4">
        Media
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6"

        x-data="{
    poster: '{{ $content->poster_url ?? '' }}',
    poster_preview: '{{ $content->poster_url ? (Str::startsWith($content->poster_url, 'http') ? $content->poster_url : asset('storage/'.$content->poster_url)) : '' }}',

    thumbnail: '{{ $content->thumbnail_url ?? '' }}',
    thumbnail_preview: '{{ $content->thumbnail_url ? (Str::startsWith($content->thumbnail_url, 'http') ? $content->thumbnail_url : asset('storage/'.$content->thumbnail_url)) : '' }}',

    backdrop: '{{ $content->backdrop_url ?? '' }}',
    backdrop_preview: '{{ $content->backdrop_url ? (Str::startsWith($content->backdrop_url, 'http') ? $content->backdrop_url : asset('storage/'.$content->backdrop_url)) : '' }}',
}"

        x-on:media-selected.window="
            if ($event.detail.target === 'poster') {
                poster = $event.detail.path;
                poster_preview = $event.detail.url;
            }

            if ($event.detail.target === 'thumbnail') {
                thumbnail = $event.detail.path;
                thumbnail_preview = $event.detail.url;
            }

            if ($event.detail.target === 'backdrop') {
                backdrop = $event.detail.path;
                backdrop_preview = $event.detail.url;
            }
        ">

        <!-- ===================== -->
        <!-- POSTER -->
        <!-- ===================== -->
        <div>
            <label class="text-xs text-gray-400 mb-1 block">Poster</label>

            <x-admin.media-manager
                folder="images/posters"
                accept="image/*"
                data-target="poster" />

            <template x-if="poster_preview">
                <img :src="poster_preview"
                    class="mt-2 h-32 rounded border border-zinc-700 object-cover">
            </template>

            <!-- ✅ FIXED -->
            <input type="hidden" name="poster" x-model="poster">
        </div>

        <!-- ===================== -->
        <!-- THUMBNAIL -->
        <!-- ===================== -->
        <div>
            <label class="text-xs text-gray-400 mb-1 block">Thumbnail</label>

            <x-admin.media-manager
                folder="images/thumbnails"
                accept="image/*"
                data-target="thumbnail" />

            <template x-if="thumbnail_preview">
                <img :src="thumbnail_preview"
                    class="mt-2 h-32 rounded border border-zinc-700 object-cover">
            </template>

            <!-- ✅ FIXED -->
            <input type="hidden" name="thumbnail" x-model="thumbnail">
        </div>

        <!-- ===================== -->
        <!-- BACKDROP -->
        <!-- ===================== -->
        <div>
            <label class="text-xs text-gray-400 mb-1 block">Backdrop</label>

            <x-admin.media-manager
                folder="images/backdrops"
                accept="image/*"
                data-target="backdrop" />

            <template x-if="backdrop_preview">
                <img :src="backdrop_preview"
                    class="mt-2 h-32 rounded border border-zinc-700 object-cover">
            </template>

            <!-- ✅ FIXED -->
            <input type="hidden" name="backdrop" x-model="backdrop">
        </div>

    </div>

</x-admin.card>