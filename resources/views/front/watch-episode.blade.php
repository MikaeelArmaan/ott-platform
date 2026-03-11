@extends('front.layout.app')

@section('title','Watch - '.$content->title.' • '.$episode->title)

@section('content')
<div class="fixed inset-0 bg-black flex items-center justify-center">

  <a href="{{ route('title.show',$content->id) }}"
     class="absolute top-6 left-6 z-50 bg-black/60 hover:bg-black px-4 py-2 rounded text-white text-sm">
     ← Back
  </a>

  @php
    $asset = $episode->videoAsset;
    $hlsUrl = ($asset && $asset->status === 'ready' && $asset->hls_master_url)
            ? asset('storage/'.$asset->hls_master_url)
            : null;

    $mp4Url = $episode->video_url ? asset('storage/'.$episode->video_url) : null;
  @endphp

  <video id="player" class="w-full h-full object-contain bg-black" controls autoplay playsinline></video>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
const player = document.getElementById('player');
const contentId = {{ $content->id }};
const episodeId = {{ $episode->id }};
const resumeAt = {{ $resumeAt ?? 0 }};
const hlsUrl = @json($hlsUrl);
const mp4Url = @json($mp4Url);

function initHlsPlayer(videoEl, url) {
  if (videoEl.canPlayType('application/vnd.apple.mpegurl')) { videoEl.src = url; return; }
  if (window.Hls && Hls.isSupported()) {
    const hls = new Hls();
    hls.loadSource(url);
    hls.attachMedia(videoEl);
    window.__hls = hls;
  }
}

if (hlsUrl) initHlsPlayer(player, hlsUrl);
else if (mp4Url) player.src = mp4Url;
else alert("No episode video available yet.");

player.addEventListener('loadedmetadata', () => {
  if (resumeAt > 5 && resumeAt < player.duration - 5) player.currentTime = resumeAt;
});

// progress save with episode_id (matches your PlaybackController)
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

window.addEventListener('beforeunload', () => { if (window.__hls) window.__hls.destroy(); });
</script>
@endsection
