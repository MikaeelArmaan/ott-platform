@extends('front.layout.app')

@section('title','Home')

@section('content')

{{-- HERO SLIDER --}}
<div class="swiper w-full h-[90vh]">

  <div class="swiper-wrapper">

@foreach($featured as $f)
@php
$bg = $f->backdrop_url
    ? asset('storage/'.$f->backdrop_url)
    : ($f->poster_url
        ? asset('storage/'.$f->poster_url)
        : 'https://picsum.photos/1600/900');

@endphp

<div class="swiper-slide relative w-full h-[90vh]"
     style="
       background-image:
         linear-gradient(to right, rgba(0,0,0,.85), rgba(0,0,0,.25)),
         url('{{ $bg }}');
       background-size: cover;
       background-position: center;
     ">

  <div class="absolute bottom-24 left-0 px-6 md:px-20 max-w-xl text-white">

    <h1 class="text-4xl md:text-6xl font-bold mb-4">
      {{ $f->title }}
    </h1>

    <p class="text-gray-300 mb-6">
      {{ Str::limit($f->description,150) }}
    </p>

    <div class="flex gap-3">
      <a href="{{ route('title.watch', $f->id) }}"
        class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded font-semibold">
        ▶ Play
      </a>

      <a href="{{ route('title.show',$f->id) }}"
         class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded font-semibold">
        More Info
      </a>
    </div>

  </div>
</div>

@endforeach

  </div>

  {{-- Pagination Dots --}}
  <div class="swiper-pagination"></div>

</div>

@auth
@php
$profile = auth()->user()->profiles()->first();
$watchlist = $profile->watchlist()->latest()->take(12)->get();
@endphp

@if($watchlist->count())
<div class="px-6 md:px-20 py-6 bg-black">
<h2 class="text-xl font-semibold mb-3">My Watchlist</h2>

<div class="flex gap-4 overflow-x-auto pb-3">

@foreach($watchlist as $c)
@php
$img = $c->thumbnail_url ?: $c->poster_url;
@endphp

<a href="{{ route('title.show',$c->id) }}"
   class="min-w-[160px] bg-zinc-900 rounded-lg overflow-hidden">

<div class="h-[220px] bg-cover bg-center"
 style="background-image:url('{{ asset('storage/'.$img) }}')"></div>

</a>
@endforeach

</div>
</div>
@endif
@endauth

{{-- CONTENT ROWS --}}
<div class="px-6 md:px-20 py-10 space-y-10 bg-black">
@if(auth()->check() && $continue->count())
<div>
  <h2 class="text-xl font-semibold mb-3">Continue Watching</h2>

  <div class="flex gap-4 overflow-x-auto pb-3 scrollbar-hide relative">

    @foreach($continue as $item)
      @php
        $c = $item->content;
        $img = $c->thumbnail_url ?: $c->poster_url;
        $progress = $item->duration_seconds
            ? min(100, ($item->position_seconds / $item->duration_seconds) * 100)
            : 10;
      @endphp

      <a href="{{ route('title.watch',$c->id) }}"
         class="group min-w-[160px] bg-zinc-900 rounded-lg overflow-hidden
                transform transition hover:scale-110">

        <div class="h-[220px] bg-cover bg-center relative"
             style="background-image:url('{{ asset('storage/'.$img) }}')">

            {{-- Progress bar --}}
            <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-700">
                <div class="h-1 bg-red-600"
                     style="width:{{ $progress }}%"></div>
            </div>

        </div>

        <div class="p-2">
          <p class="text-sm font-semibold truncate">{{ $c->title }}</p>
        </div>

      </a>
    @endforeach

  </div>
</div>
@endif
{{-- Row Component --}}
@php
$rows = [
 ['Trending Now',$trending],
 ['Movies',$movies],
 ['Series',$series],
 ['Recently Added',$recent]
];
@endphp

@foreach($rows as [$label,$items])
<div>
  <h2 class="text-xl font-semibold mb-3">{{ $label }}</h2>

  <div class="flex gap-4 overflow-x-auto pb-3 scrollbar-hide">

    @foreach($items as $c)
    @php $img = $c->thumbnail_url ?: $c->poster_url; @endphp

    <div class="group relative min-w-[160px]">

<a href="{{ route('title.show',$c->id) }}"
   class="block bg-zinc-900 rounded-lg overflow-hidden
          transform transition duration-300
          group-hover:scale-125 group-hover:z-20">

    <!-- Poster -->
    <div class="h-[220px] bg-cover bg-center"
         style="background-image:url('{{ $img }}')"></div>

    <!-- Hover Overlay -->
    <div class="absolute inset-0 bg-black/70 opacity-0
                group-hover:opacity-100 transition
                flex flex-col justify-end p-3">

        <p class="text-sm font-bold mb-1">
            {{ $c->title }}
        </p>

        <p class="text-xs text-gray-400 mb-2">
            {{ strtoupper($c->type) }} • {{ $c->maturity_rating }}
        </p>

        @auth
        @php
        $profile = auth()->user()->profiles()->first();
        $inWatchlist = $profile
            ->watchlist()
            ->where('content_id',$c->id)
            ->exists();
        @endphp
        @endauth

        <div class="flex items-center gap-2">

        <a href="{{ route('title.watch',$c->id) }}"
          class="bg-white text-black px-3 py-1 rounded text-xs font-semibold">
        ▶ Play
        </a>

        <a href="{{ route('title.show',$c->id) }}"
          class="bg-gray-700 text-white px-3 py-1 rounded text-xs">
        Info
        </a>

        @auth
        <form method="POST" action="{{ route('watchlist.toggle') }}">
        @csrf
        <input type="hidden" name="content_id" value="{{ $c->id }}">

        <button
        class="bg-gray-800 text-white px-3 py-1 rounded text-xs">
        {{ $inWatchlist ? '✓' : '+' }}
        </button>
        </form>
        @endauth

        </div>

    </div>

</a>

</div>
    @endforeach

  </div>
</div>
@endforeach

</div>

@endsection
