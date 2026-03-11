@foreach($contents as $c)

@include('front.components.content-card', [
    'content' => $c,
    'watchlistIds' => $watchlistIds ?? []
])

@endforeach