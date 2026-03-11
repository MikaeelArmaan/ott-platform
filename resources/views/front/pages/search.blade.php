@extends('front.layout.app')

@section('title','Search')

@section('content')

<div class="px-6 md:px-20 py-10 bg-black min-h-screen">

<h1 class="text-2xl font-bold mb-6">
Search Results for "{{ $q }}"
</h1>

@if($contents->count() == 0)
    <p class="text-gray-400">No results found.</p>
@else

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

@foreach($contents as $c)
@php
$img = $c->thumbnail_url ?: $c->poster_url;
@endphp

<a href="{{ route('title.show',$c->id) }}"
   class="bg-zinc-900 rounded-lg overflow-hidden
          hover:scale-105 transition">

    <div class="h-[240px] bg-cover bg-center"
         style="background-image:url('{{ $img }}')"></div>

    <div class="p-2">
        <p class="text-sm font-semibold truncate">{{ $c->title }}</p>
        <p class="text-xs text-gray-400">
            {{ strtoupper($c->type) }} • {{ $c->maturity_rating }}
        </p>
    </div>

</a>
@endforeach

</div>

<div class="mt-8">
    {{ $contents->links() }}
</div>

@endif

</div>

@endsection
