@extends('front.layout.app')

@section('title', $content->title)

@section('content')

@php
$bg = $content->backdrop_url
      ?: $content->poster_url
      ?: 'https://picsum.photos/1600/900';
@endphp

{{-- ================= HERO ================= --}}
<div class="relative w-full h-[70vh] bg-cover bg-center"
     style="background-image:
     linear-gradient(to right, rgba(0,0,0,.85), rgba(0,0,0,.3)),
     url('{{ $bg }}');">

  <div class="absolute inset-0 flex items-end px-6 md:px-20 pb-20 text-white">

    <div class="max-w-2xl">

      {{-- TITLE --}}
      <h1 class="text-4xl md:text-6xl font-bold mb-4">
        {{ $content->title }}
      </h1>

      {{-- META --}}
      <div class="flex flex-wrap gap-3 text-sm text-gray-300 mb-4">
        <span>{{ strtoupper($content->type) }}</span>
        <span>{{ $content->language }}</span>
        <span>{{ $content->maturity_rating }}</span>

        @if($content->release_date)
          <span>{{ \Carbon\Carbon::parse($content->release_date)->year }}</span>
        @endif

        @if($content->runtime_seconds)
          <span>{{ gmdate("H:i", $content->runtime_seconds) }} hr</span>
        @endif
      </div>

      {{-- DESCRIPTION --}}
      <p class="text-gray-300 mb-6">
        {{ $content->description ?: 'No description available.' }}
      </p>

      {{-- RESUME --}}
      @php
      $resume = null;
      if(auth()->check()){
        $profile = auth()->user()->profiles()->first();
        $resume = \App\Models\WatchHistory::where('profile_id',$profile->id)
                    ->where('content_id',$content->id)
                    ->where('completed',false)
                    ->first();
      }
      @endphp

      @if($resume)
        <p class="text-green-400 mb-3">
          Continue from {{ gmdate("i:s",$resume->position_seconds) }}
        </p>
      @endif

      {{-- ACTIONS --}}
      <div class="flex flex-wrap gap-4 items-center">

        {{-- PLAY / WATCH --}}
        @if($content->type === 'movie')
            <a href="{{ route('title.watch',$content->id) }}"
              class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded font-semibold">
              ▶ Play
            </a>
        @else
            @php
              $firstEpisode = $content->seasons
                    ->first()?->episodes
                    ->where('is_published',true)
                    ->first();
            @endphp

            @if($firstEpisode)
              <a href="{{ route('episode.watch',[$content->id,$firstEpisode->id]) }}"
                class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded font-semibold">
                ▶ Play Episode 1
              </a>
            @endif
        @endif

        {{-- WATCHLIST --}}
        @auth
        @php
          $profile = auth()->user()->profiles()->first();
          $inWatchlist = \App\Models\Watchlist::where('profile_id',$profile->id)
                            ->where('content_id',$content->id)
                            ->exists();
        @endphp

        <form method="POST" action="{{ route('watchlist.toggle') }}">
          @csrf
          <input type="hidden" name="content_id" value="{{ $content->id }}">

          <button class="px-5 py-3 rounded font-semibold transition
            {{ $inWatchlist
              ? 'bg-gray-700 hover:bg-gray-600'
              : 'bg-gray-800 hover:bg-gray-700' }}">
            {{ $inWatchlist ? '✓ In Watchlist' : '+ Watchlist' }}
          </button>
        </form>
        @endauth

        {{-- BACK --}}
        <a href="{{ route('browse') }}"
           class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded font-semibold">
          ← Back
        </a>

      </div>

    </div>
  </div>
</div>

 
{{-- ================= EPISODES ================= --}}
@if($content->type === 'series' && $content->seasons->count())

<div class="px-6 md:px-20 py-10 bg-black text-white">

<h2 class="text-xl font-semibold mb-6">Episodes</h2>

@foreach($content->seasons as $season)

  <div class="mb-10">

    <h3 class="text-lg font-semibold mb-4">
      Season {{ $season->season_number }}
      @if($season->title) • {{ $season->title }} @endif
    </h3>

    <div class="space-y-3">

      @foreach($season->episodes as $ep)

      <a href="{{ route('episode.watch',[$content->id,$ep->id]) }}"
         class="flex gap-4 bg-zinc-900 hover:bg-zinc-800
                rounded-lg p-4 transition">

        {{-- THUMB --}}
        @php
          $thumb = $ep->thumbnail_url
              ? asset('storage/'.$ep->thumbnail_url)
              : null;
        @endphp

        <div class="w-40 h-24 bg-zinc-800 rounded
                    bg-cover bg-center"
             style="{{ $thumb ? "background-image:url('{$thumb}')" : '' }}">
        </div>

        {{-- META --}}
        <div class="flex-1">

          <div class="flex justify-between items-center">
            <p class="font-semibold">
              E{{ $ep->episode_number }} • {{ $ep->title }}
            </p>

            @if($ep->runtime_seconds)
              <span class="text-xs text-gray-400">
                {{ gmdate("H:i",$ep->runtime_seconds) }} hr
              </span>
            @endif
          </div>

          <p class="text-sm text-gray-400 mt-1">
            {{ \Illuminate\Support\Str::limit($ep->description,140) }}
          </p>

        </div>

      </a>

      @endforeach

    </div>

  </div>

@endforeach

</div>

@endif


{{-- ================= SIMILAR ================= --}}
<div class="px-6 md:px-20 py-10 bg-black">

<h2 class="text-xl font-semibold mb-4">More Like This</h2>

<div class="flex gap-4 overflow-x-auto pb-3 scrollbar-hide">

@foreach($similar as $s)
@php $img = $s->thumbnail_url ?: $s->poster_url; @endphp

<a href="{{ route('title.show',$s->id) }}"
   class="min-w-[160px] bg-zinc-900 rounded-lg overflow-hidden
          hover:scale-110 transform transition">

  <div class="h-[220px] bg-cover bg-center"
       style="background-image:url('{{ $img }}')"></div>

  <div class="p-2">
    <p class="text-sm font-semibold truncate">{{ $s->title }}</p>
    <p class="text-xs text-gray-400">{{ strtoupper($s->type) }}</p>
  </div>

</a>
@endforeach

</div>
</div>

@endsection
