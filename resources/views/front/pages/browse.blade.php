@extends('front.layout.app')

@section('title', 'Browse')

@section('content')

    <div class="px-6 md:px-20 py-10 bg-black">

        <h1 class="text-2xl font-bold mb-6">Browse</h1>

        {{-- FILTER FORM --}}
        @include('front.partials.browse-filters')


        {{-- CONTENT GRID --}}
        <div id="browse-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">

            @if ($contents->count())

                @foreach ($contents as $c)
                    @include('front.components.content-card', [
                        'content' => $c,
                        'watchlistIds' => $watchlistIds ?? [],
                    ])
                @endforeach
            @else
                @include('front.components.empty-placeholder')

            @endif

        </div>


        {{-- SKELETON LOADER --}}
        <div id="skeleton-container" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">

            @for ($i = 0; $i < 12; $i++)
                @include('front.components.content-card-skeleton')
            @endfor

        </div>


        {{-- INFINITE SCROLL TRIGGER --}}
        @if ($contents->hasMorePages())
            <div id="infinite-scroll-trigger" data-next-page="{{ $contents->currentPage() + 1 }}"
                class="h-20 flex items-center justify-center">

                <div class="text-gray-500 text-sm">
                    Scroll to load more
                </div>

            </div>
        @elseif($contents->count())
            <div class="text-center text-gray-500 mt-10">
                No more content
            </div>
        @endif

    </div>

@endsection
