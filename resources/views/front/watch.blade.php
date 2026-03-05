@extends('front.layout.app')

@section('title','Watch - '.$content->title. (isset($episode) ? ' • '.$episode->title : ''))

@section('content')
@php
  // If episode exists, use episode video asset; else use movie asset/content video
  $asset = isset($episode) ? $episode->videoAsset : $content->videoAsset;

  $hlsUrl = ($asset && $asset->status === 'ready' && $asset->hls_master_url)
          ? asset('storage/'.$asset->hls_master_url)
          : null;

  $mp4Url = null;
  if (!isset($episode)) {
      $mp4Url = $content->video_url ? asset('storage/'.$content->video_url) : null;
  }
@endphp
<div class="fixed inset-0 bg-black flex items-center justify-center">

  {{-- BACK --}}
  <a href="{{ route('title.show',$content->id) }}"
     class="absolute top-6 left-6 z-50
            bg-black/60 hover:bg-black
            px-4 py-2 rounded text-white text-sm">
     ← Back
  </a>

  {{-- VIDEO --}}
    <!-- @php
        $video = asset('storage/'.$content->video_url)
        ?: 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
    @endphp

    <video
        id="player"
        class="w-full h-full object-contain bg-black"
        controls
        autoplay
        playsinline
    >
        <source src="{{ $video }}" type="video/mp4">
    </video> -->

    @php
    $asset = $content->videoAsset;
    $hlsUrl = ($asset && $asset->status === 'ready' && $asset->hls_master_url)
            ? asset('storage/'.$asset->hls_master_url)
            : null;

    $mp4Url = $content->video_url ? asset('storage/'.$content->video_url) : null;
    @endphp
    <div id="loadingOverlay"
     class="absolute inset-0 flex items-center justify-center text-white bg-black">
    Loading video...
    </div>
    <video id="player" class="w-full h-full object-contain bg-black" controls autoplay playsinline></video>


</div>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>
const player = document.getElementById('player');
const contentId = {{ $content->id }};
const resumeAt = {{ $resumeAt ?? 0 }};
const hlsUrl = @json($hlsUrl);
const mp4Url = @json($mp4Url);

/* ========== INIT SOURCE ========== */

function initHlsPlayer(videoEl, url) {
    if (videoEl.canPlayType('application/vnd.apple.mpegurl')) {
        videoEl.src = url;
        return;
    }

    if (window.Hls && Hls.isSupported()) {
        const hls = new Hls();
        hls.loadSource(url);
        hls.attachMedia(videoEl);
        window.__hls = hls;
    }
}

if (hlsUrl) {
    initHlsPlayer(player, hlsUrl);
} else if (mp4Url) {
    player.src = mp4Url;
} else {
    alert("No video available yet.");
}

/* Autoplay safe */
player.muted = true;
player.play().catch(()=>{});

/* ========== RESUME ========== */

player.addEventListener('loadedmetadata', () => {
    if (resumeAt > 5 && resumeAt < player.duration - 5) {
        player.currentTime = resumeAt;
    }
});

/* ========== SAVE PROGRESS ========== */

setInterval(() => {
    if (!player.paused && player.duration && !isNaN(player.duration)) {
        fetch('/api/v1/playback/progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                profile_id: 1,
                content_id: contentId,
                position_seconds: Math.floor(player.currentTime),
                duration_seconds: Math.floor(player.duration)
            })
        });
    }
}, 5000);

/* Cleanup */
window.addEventListener('beforeunload', () => {
    if (window.__hls) window.__hls.destroy();
});
</script>


@endsection
