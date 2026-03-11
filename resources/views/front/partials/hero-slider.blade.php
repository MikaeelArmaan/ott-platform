<div class="hero swiper w-full h-[90vh]">

<div class="swiper-wrapper">

@foreach($featured as $f)

@php
$bg = $f->backdrop_url
    ? asset('storage/'.$f->backdrop_url)
    : ($f->poster_url
        ? asset('storage/'.$f->poster_url)
        : 'https://picsum.photos/1600/900');
@endphp

<div class="swiper-slide relative w-full h-[90vh]"
     style="
       background-image:
       linear-gradient(to right, rgba(0,0,0,.85), rgba(0,0,0,.25)),
       url('{{ $bg }}');
       background-size: cover;
       background-position: center;
     ">

<div class="absolute bottom-24 left-0 px-6 md:px-20 max-w-xl text-white">

<h1 class="text-4xl md:text-6xl font-bold mb-4">
{{ $f->title }}
</h1>

<p class="text-gray-300 mb-6">
{{ Str::limit($f->description,150) }}
</p>

<div class="flex gap-3">

<a href="{{ route('title.watch',$f->id) }}"
class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded font-semibold">
▶ Play
</a>

<a href="{{ route('title.show',$f->id) }}"
class="bg-gray-700 hover:bg-gray-600 px-6 py-3 rounded font-semibold">
More Info
</a>

</div>

</div>

</div>

@endforeach

</div>

<div class="swiper-pagination"></div>

</div>