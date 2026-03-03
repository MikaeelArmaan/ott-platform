@extends('front.layout.app')

@section('title','My Watchlist')

@section('content')

<div class="px-6 md:px-20 py-10">

<h1 class="text-2xl font-semibold mb-6">My Watchlist</h1>

@if($items->count()==0)
<p class="text-gray-400">Your watchlist is empty.</p>
@endif

<div class="grid grid-cols-2 md:grid-cols-5 gap-4">

@foreach($items as $c)

@php
$img = $c->thumbnail_url ?: $c->poster_url;
@endphp

<a href="{{ route('title.show',$c->id) }}"
   class="bg-zinc-900 rounded-lg overflow-hidden hover:scale-105 transition">

    <div class="h-[220px] bg-cover bg-center"
         style="background-image:url('{{ asset('storage/'.$img) }}')"></div>

    <div class="p-2">
        <p class="text-sm font-semibold truncate">{{ $c->title }}</p>
        <p class="text-xs text-gray-400">{{ strtoupper($c->type) }}</p>
    </div>

</a>

@endforeach

</div>
</div>

@endsection
