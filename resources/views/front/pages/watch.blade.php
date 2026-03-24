@extends('front.layout.app')

@section('title', 'Watch - ' . $content->title . (isset($episode) ? ' • ' . $episode->title : ''))

@section('content')

@php
use Illuminate\Support\Str;

if (!function_exists('videoUrl')) {
function videoUrl($url) {
return Str::startsWith($url, ['http://', 'https://'])
? $url
: asset('storage/' . ltrim($url, '/'));
}
}

$hlsUrl = null;
$mp4Url = null;
$asset = null;

/* Resolve video */
if (isset($episode) && $episode->videoAsset) {
$asset = $episode->videoAsset;
}

if (!$asset && $content->videoAsset) {
$asset = $content->videoAsset;
}

/* Build URLs */
if ($asset) {
$mp4Url = $asset->path ? videoUrl($asset->path) : null;

if ($asset->is_processed) {
$hlsPath = $asset->episode_id
? 'hls/episode/' . $asset->episode_id . '/master.m3u8'
: 'hls/content/' . $asset->content_id . '/master.m3u8';

$hlsUrl = videoUrl($hlsPath);
}
}

$profileId = auth()->user()?->profiles()->first()?->id;
@endphp

<div class="bg-black min-h-screen text-white">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid lg:grid-cols-12 gap-6">

            {{-- PLAYER --}}
            <div class="lg:col-span-8">

                <div class="relative bg-black rounded-xl overflow-hidden shadow-2xl">

                    {{-- LOADER --}}
                    <div id="loadingOverlay"
                        class="absolute inset-0 flex items-center justify-center bg-black/70 z-20">
                        <div class="animate-spin w-6 h-6 border-2 border-white border-t-transparent rounded-full"></div>
                    </div>

                    {{-- VIDEO --}}
                    <video
                        id="player"
                        class="w-full aspect-video bg-black"
                        controls
                        playsinline
                        preload="metadata">
                    </video>

                    {{-- SKIP INTRO --}}
                    <button
                        id="skipIntro"
                        class="hidden absolute bottom-24 right-6 z-20 bg-white/10 backdrop-blur
                               hover:bg-white/20 text-white text-sm px-4 py-2 rounded-full transition">
                        ⏭ Skip Intro
                    </button>
                </div>

                {{-- TITLE + META --}}
                <div class="mt-4 space-y-2">

                    {{-- EPISODE INFO --}}
                    @if(isset($episode))
                    <div class="text-sm text-gray-400 flex items-center gap-2">
                        Season {{ $episode->season?->season_number }}
                        • Episode {{ $episode->episode_number }}

                        @if($episode->duration)
                        • {{ formatDuration($episode->duration) }}
                        @endif
                    </div>
                    @endif

                    {{-- TITLE --}}
                    <h1 class="text-xl font-semibold">
                        {{ $content->title }}

                        @if(isset($episode))
                        <span class="text-gray-400">• {{ $episode->title }}</span>
                        @endif
                    </h1>

                    {{-- META ROW (YEAR • DURATION • GENRES) --}}
                    <div class="text-sm text-gray-400 flex flex-wrap items-center gap-2">

                        {{-- CONTENT RELEASE YEAR --}}
                        @if($content->release_year)
                        <span>Released • {{ $content->release_year }}</span>
                        @endif

                        {{-- DOT --}}
                        @if($content->release_year && ($asset?->release_at || $content->duration || isset($episode)))
                        <span>•</span>
                        @endif

                        {{-- EPISODE / VIDEO RELEASE DATE --}}
                        @if($asset && $asset->release_at)
                        <span>
                            {{ $asset->release_at->format('M d, Y') }}
                            <span class="text-gray-500">
                                {{ $asset->release_at->format('h:i A') }}
                            </span>
                        </span>
                        @endif

                        {{-- DOT --}}
                        @if(($asset?->release_at) && ($content->duration || isset($episode)))
                        <span>•</span>
                        @endif

                        {{-- DURATION --}}
                        @if(isset($episode) && $episode->duration)
                        <span>{{ formatDuration($episode->duration) }}</span>
                        @elseif(!isset($episode) && $content->duration)
                        <span>{{ formatDuration($content->duration) }}</span>
                        @endif

                        {{-- DOT --}}
                        @if(($content->duration || isset($episode)) && $content->genres->count())
                        <span>•</span>
                        @endif

                        {{-- GENRES --}}
                        @foreach($content->genres as $genre)
                        <span>{{ $genre->name }}</span>
                        @if(!$loop->last) <span>,</span> @endif
                        @endforeach

                    </div>
                    {{-- TAG CHIPS --}}
                    @if($content->genres && $content->genres->count())
                    <div class="flex flex-wrap gap-2 mt-2">

                        @foreach($content->genres as $genre)
                        <span
                            class="text-xs px-3 py-1 rounded-full 
                                           bg-white/10 text-gray-300 
                                           hover:bg-white/20 transition cursor-pointer">
                            {{ $genre->name }}
                        </span>
                        @endforeach

                    </div>
                    @endif

                </div>

                {{-- ACTIONS --}}
                <div class="flex items-center gap-4 mt-4 text-sm">
                    <button class="bg-neutral-800 hover:bg-neutral-700 px-4 py-2 rounded">
                        👍 Like
                    </button>

                    <button class="bg-neutral-800 hover:bg-neutral-700 px-4 py-2 rounded">
                        🔗 Share
                    </button>

                    <button class="bg-neutral-800 hover:bg-neutral-700 px-4 py-2 rounded">
                        ➕ Watchlist
                    </button>
                </div>

                {{-- DESCRIPTION --}}
                <div class="bg-neutral-900 p-4 rounded mt-4 text-sm">
                    @if(isset($episode))
                    {{ $episode->description }}
                    @else
                    {{ $content->description }}
                    @endif
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="lg:col-span-4 lg:sticky lg:top-6 h-fit">

                {{-- EPISODES --}}
                @if ($episodes->count())
                <h3 class="font-semibold mb-3">Episodes</h3>

                <div class="space-y-3 max-h-[600px] overflow-y-auto">

                    @foreach ($episodes as $ep)

                    <a
                        href="{{ route('title.watch.episode', [$content->id, $ep->id]) }}"
                        class="flex gap-3 p-2 rounded transition
                            @if(isset($episode) && $episode->id === $ep->id)
                                bg-white/10 border border-white/10
                            @else
                                hover:bg-white/5
                            @endif">

                        <img
                            src="{{ asset('storage/' . ($ep->thumbnail ?? '')) }}"
                            class="w-28 h-16 object-cover rounded"
                            alt="{{ $ep->title }}">

                        <div class="text-sm">
                            <div class="font-semibold">
                                S{{ $ep->season?->season_number ?? 1 }}
                                • E{{ $ep->episode_number }} - {{ $ep->title }}
                            </div>

                            <div class="text-gray-400 text-xs line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($ep->description, 80) }}
                            </div>
                        </div>

                    </a>

                    @endforeach

                </div>
                @endif

                {{-- RECOMMENDED --}}
                @if (isset($recommended) && count($recommended))
                <h3 class="font-semibold mt-6 mb-3">More Videos</h3>

                <div class="space-y-4">
                    @foreach ($recommended as $rec)
                    <a
                        href="{{ route('title.watch', $rec->id) }}"
                        class="flex gap-3 hover:bg-neutral-900 p-2 rounded">

                        <img
                            src="{{ asset('storage/' . ($rec->poster_url ?? $rec->poster ?? '')) }}"
                            class="w-32 h-20 object-cover rounded">

                        <div class="text-sm">
                            <div class="font-semibold line-clamp-2">
                                {{ $rec->title }}
                            </div>

                            <div class="text-gray-400 text-xs mt-1">
                                {{ $rec->year ?? '' }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- NEXT EPISODE --}}
<div id="nextEpisodePopup"
    class="hidden fixed bottom-6 right-6 bg-black/90 p-4 rounded shadow-xl w-72">
    <div class="text-sm mb-2">
        Next episode starting...
    </div>

    <button id="playNextEpisode"
        class="bg-red-600 px-4 py-2 rounded w-full">
        Play Next Episode
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const player = document.getElementById("player");
        const loader = document.getElementById("loadingOverlay");
        const skipBtn = document.getElementById("skipIntro");

        const hlsUrl = @json($hlsUrl);
        const mp4Url = @json($mp4Url);

        function hideLoader() {
            loader.style.display = "none";
        }

        function showLoader() {
            loader.style.display = "flex";
        }

        function initHls(url) {
            if (!url) return false;

            if (player.canPlayType("application/vnd.apple.mpegurl")) {
                player.src = url;
                return true;
            }

            if (window.Hls && Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(player);
                return true;
            }

            return false;
        }

        function initMp4(url) {
            if (!url) return false;
            player.src = url;
            return true;
        }

        if (hlsUrl) initHls(hlsUrl);
        else if (mp4Url) initMp4(mp4Url);
        else {
            hideLoader();
            alert("Video not available");
            return;
        }

        player.addEventListener("canplay", hideLoader);
        player.addEventListener("waiting", showLoader);

        player.play().catch(() => {});
    });
</script>

@endsection