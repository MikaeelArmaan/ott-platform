<div class="hero swiper w-full min-h-[500px] h-[85svh] md:h-[90dvh] max-h-[900px]">

  <div class="swiper-wrapper">

    @foreach($featured as $f)

    @php
    $bg = null;

    // backdrop
    if (!empty($f->backdrop_url)) {
    $bg = Str::startsWith($f->backdrop_url, 'http')
    ? $f->backdrop_url
    : asset('storage/' . ltrim($f->backdrop_url, '/'));
    }

    // fallback to poster
    elseif (!empty($f->poster_url)) {
    $bg = Str::startsWith($f->poster_url, 'http')
    ? $f->poster_url
    : asset('storage/' . ltrim($f->poster_url, '/'));
    }

    // fallback placeholder
    else {
    $bg = 'https://picsum.photos/1600/900';
    }
    @endphp

    <div class="swiper-slide relative w-full min-h-[500px] h-[85svh] md:h-[90dvh] max-h-[900px]">

      {{-- BACKGROUND IMAGE --}}
      <div class="absolute inset-0">
        <img
          src="{{ $bg }}"
          loading="eager"
          decoding="async"
          class="w-full h-full object-cover"
          alt="{{ $f->title }}">
      </div>

      {{-- BLUR --}}
      <div class="absolute inset-0 backdrop-blur-[1px]"></div>

      {{-- GRADIENT --}}
      <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>

      {{-- LEFT FADE --}}
      <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-black to-transparent"></div>

      {{-- BOTTOM FADE --}}
      <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-black via-black/70 to-transparent"></div>

      {{-- CONTENT --}}
      <div class="relative z-10 h-full flex items-end">

        <div class="w-full mx-auto">
          <div class="px-6 md:px-20 pb-16 md:pb-24 max-w-2xl text-white">

            <div class="space-y-4 backdrop-blur-[2px] bg-black/10 p-4 rounded-lg">

              {{-- TITLE --}}
              <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-bold leading-tight">
                {{ $f->title }}
              </h1>

              {{-- META --}}
              <div class="flex items-center gap-3 text-sm text-gray-300">

                @if($f->release_date)
                <span>{{ \Carbon\Carbon::parse($f->release_date)->format('Y') }}</span>
                @endif

                @if($f->runtime_seconds)
                <span>• {{ formatDuration($f->runtime_seconds) }}</span>
                @endif

                @if($f->maturity_rating)
                <span class="border border-gray-400 px-1 rounded text-xs">
                  {{ $f->maturity_rating }}
                </span>
                @endif

              </div>

              {{-- DESCRIPTION --}}
              <p class="text-gray-300 line-clamp-3">
                {{ \Illuminate\Support\Str::limit($f->description,150) }}
              </p>

              {{-- BUTTONS --}}
              <div class="flex gap-3 flex-wrap">

                <a href="{{ route('title.watch',$f->id) }}"
                  class="flex items-center gap-2 bg-red-600 hover:bg-red-700 px-6 py-3 rounded font-semibold transition shadow-lg">
                  ▶ <span>Play</span>
                </a>

                <a href="{{ route('title.show',$f->id) }}"
                  class="flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur px-6 py-3 rounded font-semibold transition">
                  ⓘ <span>More Info</span>
                </a>

              </div>

            </div>

          </div>
        </div>

      </div>

    </div>

    @endforeach

  </div>

  <div class="swiper-pagination"></div>

</div>