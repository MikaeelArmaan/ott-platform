@extends('front.layout.app')

@section('title', 'Watch - ' . $content->title . (isset($episode) ? ' • ' . $episode->title : ''))

@section('content')

@php

use Illuminate\Support\Str;

function videoUrl($url) {
return Str::startsWith($url, ['http://','https://'])
? $url
: asset('storage/'.$url);
}

$hlsUrl = null;
$mp4Url = null;

/*
|--------------------------------------------------------------------------
| Episode Video
|--------------------------------------------------------------------------
*/

if(isset($episode)) {

if($episode->videoAsset && $episode->videoAsset->is_processed) {

$mp4Url = videoUrl($episode->videoAsset->path);

}

}

/*
|--------------------------------------------------------------------------
| Movie / Content Video
|--------------------------------------------------------------------------
*/

if(!$mp4Url) {

if($content->videoAsset && $content->videoAsset->is_processed) {

$mp4Url = videoUrl($content->videoAsset->path);

}

}

@endphp

@php
$profileId = auth()->user()?->profiles()->first()->id ?? null;
@endphp
<div class="bg-black min-h-screen text-white">

    <div class="max-w-7xl mx-auto px-4 py-6">

        <div class="grid lg:grid-cols-12 gap-6">

            {{-- PLAYER COLUMN --}}
            <div class="lg:col-span-8">

                <div class="relative bg-black rounded-lg overflow-hidden">

                    <div id="loadingOverlay" class="absolute inset-0 flex items-center justify-center bg-black/80 z-10">
                        Loading video...
                    </div>

                    <video id="player" class="w-full aspect-video bg-black" controls autoplay playsinline>
                    </video>


                    {{-- SKIP INTRO --}}
                    <button id="skipIntro" class="hidden absolute bottom-20 right-6 bg-black/70 px-4 py-2 rounded">
                        Skip Intro
                    </button>

                </div>


                {{-- PLAYBACK SPEED --}}
                <div class="flex gap-2 mt-3">

                    <button onclick="setSpeed(1)" class="px-3 py-1 bg-neutral-800 rounded">1x</button>
                    <button onclick="setSpeed(1.25)" class="px-3 py-1 bg-neutral-800 rounded">1.25x</button>
                    <button onclick="setSpeed(1.5)" class="px-3 py-1 bg-neutral-800 rounded">1.5x</button>
                    <button onclick="setSpeed(2)" class="px-3 py-1 bg-neutral-800 rounded">2x</button>

                </div>


                {{-- TITLE --}}
                <h1 class="text-xl font-semibold mt-4">

                    {{ $content->title }}

                    @if (isset($episode))
                    • {{ $episode->title }}
                    @endif

                </h1>


                {{-- ACTIONS --}}
                <div class="flex items-center gap-4 mt-3 text-sm">

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

                    {{ $content->description }}

                </div>

            </div>



            {{-- SIDEBAR --}}
            <div class="lg:col-span-4">

                {{-- SERIES EPISODES --}}
                @if (isset($episodes) && count($episodes))

                <h3 class="font-semibold mb-3">Episodes</h3>

                <div class="space-y-3 max-h-[600px] overflow-y-auto">

                    @foreach ($episodes as $ep)
                    <a href="{{ route('title.watch.episode', [$content->id, $ep->id]) }}"
                        class="flex gap-3 p-2 rounded hover:bg-neutral-900
@if (isset($episode) && $episode->id === $ep->id) bg-neutral-800 @endif">

                        <img src="{{ asset('storage/' . $ep->thumbnail) }}"
                            class="w-28 h-16 object-cover rounded">

                        <div class="text-sm">

                            <div class="font-semibold">
                                Episode {{ $ep->episode_number }}
                            </div>

                            <div class="text-gray-400 text-xs line-clamp-2">
                                {{ $ep->title }}
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
                    <a href="{{ route('title.watch', $rec->id) }}"
                        class="flex gap-3 hover:bg-neutral-900 p-2 rounded">

                        <img src="{{ asset('storage/' . $rec->poster) }}"
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



{{-- NEXT EPISODE POPUP --}}
<div id="nextEpisodePopup" class="hidden fixed bottom-6 right-6 bg-black/90 p-4 rounded shadow-xl w-72">

    <div class="text-sm mb-2">
        Next episode starting...
    </div>

    <button id="playNextEpisode" class="bg-red-600 px-4 py-2 rounded w-full">
        Play Next Episode
    </button>

</div>



{{-- PLAYER SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const player = document.getElementById('player');
        const loader = document.getElementById('loadingOverlay');
        const skipBtn = document.getElementById('skipIntro');
        const episodeId = {{ isset($episode) ? $episode->id : 'null' }};

        const contentId = {{ $content->id }};
        const resumeAt = {{$resumeAt ?? 0}};
        const hlsUrl = @json($hlsUrl);
        const mp4Url = @json($mp4Url);



        function initHls(video, url) {

            if (player.canPlayType('application/vnd.apple.mpegurl')) {
                player.src = url;
                return;
            }

            if (window.Hls && Hls.isSupported()) {

                const hls = new Hls();

                hls.loadSource(url);
                hls.attachMedia(player);

                window.__hls = hls;

            }

        }



        if (hlsUrl) {
            initHls(player, hlsUrl);
        } else if (mp4Url) {
            player.src = mp4Url;
        } else {
            alert("Video not available");
        }



        player.muted = true;
        player.play().catch(() => {});



        player.addEventListener('loadedmetadata', () => {

            if (resumeAt > 5 && resumeAt < player.duration - 5) {
                player.currentTime = resumeAt;
            }

        });



        player.addEventListener('waiting', () => {
            loader.style.display = "flex";
        });

        player.addEventListener('playing', () => {
            loader.style.display = "none";
        });



        player.addEventListener('timeupdate', () => {

            if (player.currentTime >= 60 && player.currentTime <= 90) {
                skipBtn.classList.remove("hidden");
            } else {
                skipBtn.classList.add("hidden");
            }

        });

        skipBtn.onclick = () => {
            player.currentTime = 90;
        };



        setInterval(() => {

            if (!player.paused && player.duration) {

                fetch('/api/v1/playback/progress', {

                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },

                    body: JSON.stringify({

                        profile_id: 1,
                        content_id: contentId,
                        episode_id: {{ $episode->id ?? 'null' }},

                        position_seconds: Math.floor(player.currentTime),
                        duration_seconds: Math.floor(player.duration)

                    })

                });

            }

        }, 5000);



        document.addEventListener('keydown', (e) => {

            switch (e.key) {

                case ' ':
                    e.preventDefault();
                    player.paused ? player.play() : player.pause();
                    break;

                case 'ArrowRight':
                    player.currentTime += 10;
                    break;

                case 'ArrowLeft':
                    player.currentTime -= 10;
                    break;

                case 'f':
                    if (document.fullscreenElement) {
                        document.exitFullscreen();
                    } else {
                        player.requestFullscreen();
                    }
                    break;

            }

        });



        player.addEventListener('ended', () => {

            const nextUrl = @json(isset($nextEpisode) ? route('title.watch.episode', [$content->id, $nextEpisode->id]) : null);

            if (nextUrl) {

                const popup = document.getElementById("nextEpisodePopup");
                const btn = document.getElementById("playNextEpisode");

                popup.classList.remove("hidden");

                btn.onclick = () => window.location.href = nextUrl;

                setTimeout(() => {
                    window.location.href = nextUrl;
                }, 5000);

            }

        });



        window.setSpeed = function(rate) {
            player.playbackRate = rate;
        };



        window.addEventListener('beforeunload', () => {
            if (window.__hls) window.__hls.destroy();
        });

    });
</script>

@endsection