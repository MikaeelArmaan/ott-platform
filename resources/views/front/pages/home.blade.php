@extends('front.layout.app')

@section('title','Home')

@section('content')

@include('front.partials.hero-slider',['featured'=>$featured])

@auth
<div id="watchlist-wrapper">
    @include('front.partials.watchlist-row',['watchlist'=>$watchlist])
</div>
@endauth

<div class="px-6 md:px-20 py-10 space-y-10 bg-black">

    @includeWhen(
    auth()->check() && isset($continueWatching) && $continueWatching->count(),
    'front.partials.continue-watching-row',
    ['continueWatching'=>$continueWatching]
    )

    @php
    $rows = [
    ['Trending Now',$trending],
    ['Movies',$movies],
    ['Series',$series],
    ['Recently Added',$recent],
    ];
    @endphp

    @foreach($rows as [$label,$items])

    @include('front.partials.content-row',[
    'title'=>$label,
    'items'=>$items,
    'watchlistIds'=>$watchlistIds ?? []
    ])

    @endforeach

</div>

@endsection