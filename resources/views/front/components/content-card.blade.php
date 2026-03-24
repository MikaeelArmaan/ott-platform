@php
$img = $content->thumbnail_url
    ?: $content->poster_url
    ?: $content->backdrop_url
    ?: 'https://picsum.photos/300/450';

$inWatchlist = in_array($content->id, $watchlistIds ?? []);
$progressPercent = $progress->completion_percent ?? null;
@endphp

<div class="group relative min-w-[160px] hover:z-40">

<a href="{{ route('title.show',$content->id) }}"
   class="block rounded-lg overflow-hidden bg-zinc-900
          transition-all duration-300
          group-hover:scale-125 group-hover:-translate-y-4">

    {{-- POSTER --}}
    <div class="relative h-[220px] bg-cover bg-center"
         style="background-image:url('{{ $img }}')">

        {{-- PROGRESS BAR --}}
        @if($progressPercent)
        <div class="absolute bottom-0 left-0 right-0 h-[3px] bg-gray-700">
            <div class="h-[3px] bg-red-600"
                 style="width: {{ $progressPercent }}%">
            </div>
        </div>
        @endif

    </div>


    {{-- EXPANDED CONTENT --}}
    <div class="absolute left-0 right-0 bottom-0
                bg-zinc-700
                opacity-0
                rounded-lg
                group-hover:opacity-100
                transition duration-300
                p-3">

        {{-- ACTION BUTTONS --}}
        <div class="flex items-center gap-2 mb-2">

            {{-- PLAY --}}
            <a href="{{ route('title.watch',$content->id) }}"
               class="bg-white text-black w-7 h-7 flex items-center justify-center rounded-full text-xs">
                ▶
            </a>

            {{-- INFO --}}
            <a href="{{ route('title.show',$content->id) }}"
               class="bg-gray-700 text-white w-7 h-7 flex items-center justify-center rounded-full text-xs">
                i
            </a>

            {{-- WATCHLIST --}}
            @auth
            
            <form class="watchlist-form"
                  data-content-id="{{ $content->id }}">

                @csrf

                <button type="button"
                    class="watchlist-btn bg-gray-800 text-white
                           w-7 h-7 flex items-center justify-center
                           rounded-full text-xs">

                    {{ $inWatchlist ? '✓' : '+' }}

                </button>

            </form>

            @endauth

        </div>


        {{-- TITLE --}}
        <p class="text-sm font-semibold line-clamp-2 mb-1">
            {{ $content->title }}
        </p>


        {{-- META --}}
        <p class="text-xs text-gray-400">

            {{ strtoupper($content->type) }}

            @if($content->maturity_rating)
            • {{ $content->maturity_rating }}
            @endif

        </p>

    </div>

</a>

</div>