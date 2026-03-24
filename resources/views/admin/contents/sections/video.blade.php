<div class="space-y-4">

    {{-- LABEL --}}
    <label class="text-sm text-gray-400">
        Upload / Select Movie Video
    </label>

    {{-- MEDIA MANAGER --}}
    <x-admin.media-manager
        name="video"
        :value="$content->videoAsset->path ?? null"
        folder="videos"
        accept="video/mp4,video/webm" />

    {{-- REACTIVE PREVIEW --}}
    <div
        x-data="{ video: '{{ $content->videoAsset->path ?? '' }}' }"
        x-init="
            $watch('$root.selected', val => {
                if(val && val.type === 'video') video = val.path
            })
        ">

        {{-- MP4 PREVIEW --}}
        <template x-if="video">
            <div class="mt-4 bg-black rounded overflow-hidden">
                <video controls class="w-full h-56">
                    <source :src="'/storage/' + video" type="video/mp4">
                </video>
            </div>
        </template>

        {{-- HLS PREVIEW --}}
        @if($content->videoAsset && $content->videoAsset->is_processed)
        <div class="mt-4 bg-black rounded overflow-hidden">
            <video id="hls-player" controls class="w-full h-56"></video>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const video = document.getElementById('hls-player');
                const src = "{{ asset('storage/hls/'.$content->id.'/master.m3u8') }}";

                if (window.Hls && Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(src);
                    hls.attachMedia(video);
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = src;
                }

            });
        </script>
        @endif

    </div>

</div>