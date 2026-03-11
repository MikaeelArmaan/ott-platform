<div class="content-row">

    <h2 class="text-xl font-semibold mb-4">
        {{ $title }}
    </h2>

    <div class="content-scroll flex overflow-x-auto gap-4 pb-4 scrollbar-hide touch-pan-x">

        @foreach ($items as $item)

            @php
                $content = $item->content ?? $item; // supports WatchHistory or Content
                $progress = isset($item->completion_percent) ? $item : null;
            @endphp

            <div class="flex-shrink-0 w-[150px] md:w-[170px]">

                @include('front.components.content-card', [
                    'content' => $content,
                    'progress' => $progress,
                    'watchlistIds' => $watchlistIds ?? [],
                ])

            </div>

        @endforeach

    </div>

</div>