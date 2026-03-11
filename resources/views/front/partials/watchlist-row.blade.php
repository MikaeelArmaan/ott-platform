<div id="watchlist-wrapper">

   @if($watchlist->count())

   <div class="px-6 md:px-20 py-6 bg-black">

      <h2 class="text-xl font-semibold mb-4">
         My Watchlist
      </h2>

      <div id="watchlist-row"
         class="flex overflow-x-auto gap-4 pb-4 scrollbar-hide touch-pan-x">

         @foreach($watchlist as $c)

         <div class="flex-shrink-0 w-[150px] md:w-[170px]">

            @include('front.components.content-card', [
            'content' => $c,
            'watchlistIds' => $watchlist->pluck('id')->toArray()
            ])

         </div>

         @endforeach

      </div>

   </div>

   @endif

</div>