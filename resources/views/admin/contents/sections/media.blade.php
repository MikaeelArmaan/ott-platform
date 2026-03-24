<x-admin.card>

    <h2 class="text-lg font-semibold mb-4">
        Media
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <x-admin.image-upload
            name="poster"
            label="Poster"
            previewId="posterPreview"
            :value="$content->poster_url ?? null" />

        <x-admin.image-upload
            name="thumbnail"
            label="Thumbnail"
            previewId="thumbnailPreview"
            :value="$content->thumbnail_url ?? null" />

        <x-admin.image-upload
            name="backdrop"
            label="Backdrop"
            previewId="backdropPreview"
            :value="$content->backdrop_url ?? null" />

    </div>

</x-admin.card>