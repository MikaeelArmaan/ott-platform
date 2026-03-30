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

    <!-- STATUS + PLAYER -->
    @if($content->videoAsset)

    <!-- STATUS BAR -->
    <div class="flex items-center justify-between bg-zinc-900 border border-zinc-800 rounded-lg px-4 py-3">

        <div class="flex items-center gap-3">

            <!-- STATUS DOT -->
            <div class="w-3 h-3 rounded-full
                    {{ $content->videoAsset->is_processed ? 'bg-green-500' : 'bg-yellow-500 animate-pulse' }}">
            </div>

            <!-- STATUS TEXT -->
            <div>
                @if($content->videoAsset->is_processed)
                <p class="text-sm text-green-400 font-medium">
                    Ready for streaming
                </p>
                <p class="text-xs text-gray-500">
                    HLS video is available
                </p>
                @else
                <p class="text-sm text-yellow-400 font-medium">
                    Processing video...
                </p>
                <p class="text-xs text-gray-500">
                    Encoding HLS (this may take a few minutes)
                </p>
                @endif
            </div>

        </div>

        <!-- OPTIONAL ACTION -->
        @if($content->videoAsset->is_processed)
        <span class="text-xs text-gray-500">
            Adaptive Streaming Enabled
        </span>
        @endif

    </div>

    @endif

</div>