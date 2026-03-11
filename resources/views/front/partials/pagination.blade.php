@if ($paginator->hasPages())

    <nav class="flex justify-center items-center gap-2">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-gray-500 bg-zinc-900 rounded">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="px-3 py-2 bg-zinc-900 hover:bg-red-600 rounded transition">
                ‹
            </a>
        @endif


        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-2 text-gray-400">
                    {{ $element }}
                </span>
            @endif


            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 bg-red-600 text-white rounded font-semibold">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 bg-zinc-900 hover:bg-red-600 rounded transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach


        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 bg-zinc-900 hover:bg-red-600 rounded transition">
                ›
            </a>
        @else
            <span class="px-3 py-2 text-gray-500 bg-zinc-900 rounded">
                ›
            </span>
        @endif

    </nav>

@endif
