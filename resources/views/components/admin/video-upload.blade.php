@props([
'name',
'label',
'value' => null,
'hls' => null
])

<div
    x-data="{
        preview: false,
        video_preview: null,
        video: '{{ $value ?? '' }}',
        hls: '{{ $hls ?? '' }}',

        file_name: null,
        file_size: null,

        previewVideo(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.video_preview = URL.createObjectURL(file);
            this.preview = true;

            this.file_name = file.name;
            this.file_size = (file.size / 1024 / 1024).toFixed(2);

            // 🔥 FORCE VIDEO RELOAD
            this.$nextTick(() => {
                if (this.$refs.player) {
                    this.$refs.player.load();
                }
            });
        }
    }"
    class="space-y-3">

    <!-- LABEL -->
    <label class="text-sm text-gray-400">
        {{ $label }}
    </label>

    <!-- EXISTING PREVIEW (STATIC) -->
    @if($value || $hls)
    <div class="bg-black rounded overflow-hidden">
        <video controls class="w-full h-48">
            @if($hls)
            <source src="{{ $hls }}" type="application/x-mpegURL">
            @else
            <source src="{{ asset('storage/'.$value) }}" type="video/mp4">
            @endif
        </video>
    </div>
    @endif

    <!-- TOGGLE -->
    <button type="button"
        @click="preview = !preview"
        class="bg-red-600 px-3 py-1 rounded text-white text-sm">
        Toggle Preview
    </button>

    <!-- DYNAMIC PREVIEW -->
    <template x-if="video_preview || video || hls">
        <div x-show="preview" x-transition class="bg-black rounded overflow-hidden">

            <video x-ref="player" controls class="w-full h-48 border border-zinc-700 rounded">

                <source
                    :src="video_preview 
                        ? video_preview 
                        : (hls ? hls : '/storage/' + video)"
                    :type="video_preview 
                        ? 'video/mp4' 
                        : (hls ? 'application/x-mpegURL' : 'video/mp4')">

            </video>

        </div>
    </template>

    <!-- FILE INPUT -->
    <input
        type="file"
        name="{{ $name }}"
        accept="video/mp4"
        class="w-full bg-zinc-800 border border-zinc-700 rounded px-3 py-2 text-white"
        @change="previewVideo">

    <!-- FILE INFO -->
    <template x-if="file_name">
        <div class="text-xs text-green-400">
            Selected: <span x-text="file_name"></span>
            (<span x-text="file_size"></span> MB)
        </div>
    </template>

    <p class="text-xs text-gray-500">
        Upload MP4. HLS will be generated automatically.
    </p>

    @error($name)
    <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror

</div>