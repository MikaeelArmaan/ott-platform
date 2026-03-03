@extends('front.layout.app')

@section('title','Browse')

@section('content')

<div class="px-6 md:px-20 py-10 bg-black min-h-screen">

<h1 class="text-2xl font-bold mb-6">Browse</h1>
<form method="GET"
      class="flex flex-wrap gap-4 mb-6">

<select name="type"
class="bg-zinc-900 text-white px-3 py-2 rounded">
  <option value="">All Types</option>
  <option value="movie" @selected(request('type')=='movie')>Movies</option>
  <option value="series" @selected(request('type')=='series')>Series</option>
</select>

<select name="language"
class="bg-zinc-900 text-white px-3 py-2 rounded">
  <option value="">All Languages</option>
  <option>English</option>
  <option>Hindi</option>
  <option>Spanish</option>
  <option>French</option>
</select>

<select name="rating"
class="bg-zinc-900 text-white px-3 py-2 rounded">
  <option value="">All Ratings</option>
  <option>U</option>
  <option>U/A</option>
  <option>A</option>
</select>

<button
class="bg-red-600 px-4 py-2 rounded font-semibold">
Filter
</button>

<a href="{{ route('browse') }}"
class="bg-gray-700 px-4 py-2 rounded font-semibold">
Reset
</a>

</form>
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">

@foreach($contents as $c)
@php
$img = $c->thumbnail_url
     ?: $c->poster_url
     ?: $c->backdrop_url
     ?: 'https://picsum.photos/300/450';
@endphp

<a href="{{ route('title.show',$c->id) }}"
   class="group bg-zinc-900 rounded-lg overflow-hidden
          hover:scale-105 transition">

  {{-- POSTER --}}
  <div class="h-[240px] bg-cover bg-center"
       style="background-image:url('{{ $img }}')"></div>

  {{-- INFO --}}
  <div class="p-2">
    <p class="text-sm font-semibold truncate">{{ $c->title }}</p>
    <p class="text-xs text-gray-400">
      {{ strtoupper($c->type) }} • {{ $c->maturity_rating }}
    </p>
  </div>

</a>

@endforeach

</div>

{{-- PAGINATION --}}
<div class="mt-8">
  {{ $contents->links() }}
</div>

</div>

@endsection
