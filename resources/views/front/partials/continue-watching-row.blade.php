<div class="content-row">

    <h2 class="text-xl font-semibold mb-4">
        Continue Watching
    </h2>

    <div class="content-scroll flex overflow-x-auto gap-4 pb-4 scrollbar-hide touch-pan-x">

        @foreach ($continueWatching as $item)

        @php
        $content = $item->content;
        $progress = $item->completion_percent ?? 0;
        @endphp

        <div class="flex-shrink-0 w-[150px] md:w-[170px]">

            <a href="{{ route('title.watch', $content->id) }}">

                <div class="relative group rounded-lg overflow-hidden">

                    {{-- Thumbnail --}}
                    <img
                        src="{{ asset('storage/'.$content->thumbnail) }}"
                        class="object-cover w-full h-[220px] transition group-hover:scale-105">

                    {{-- Dark hover overlay --}}
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">

                        {{-- Resume Button --}}
                        <div class="bg-white/90 text-black px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">

                            ▶ Resume

                        </div>

                    </div>

                    {{-- Progress Bar --}}
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-neutral-700">

                        <div
                            class="bg-red-600 h-1"
                            style="width: {{ $progress }}%">
                        </div>

                    </div>

                </div>

            </a>

            {{-- Title --}}
            <div class="mt-2 text-sm line-clamp-2">
                {{ $content->title }}
            </div>

            {{-- Episode Info --}}
            @if($item->episode)
            <div class="text-xs text-gray-400">
                Episode {{ $item->episode->episode_number }}
            </div>
            @endif

        </div>

        @endforeach

    </div>

</div>