<div class="space-y-5">

    <!-- LABEL -->
    <div>
        <label class="text-sm font-medium text-gray-300">
            Movie Video
        </label>
        <p class="text-xs text-gray-500">
            Upload or select a video. HLS streaming will be generated automatically.
        </p>
    </div>

    <!-- MEDIA MANAGER -->
    <div class="bg-zinc-900/60 border border-zinc-800 rounded-lg p-4">
        <x-admin.media-manager
            name="video"
            :value="$content->videoAsset->path ?? null"
            folder="videos/{{$content->type}}"
            accept="video/mp4,video/webm,video/mkv,video/quicktime" />
    </div>
</div>