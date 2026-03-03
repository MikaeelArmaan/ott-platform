<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title','OTT')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

 <style>
    .hero-slide {
  opacity: 0;
  z-index: 0;
}

.hero-slide.active {
  opacity: 1;
  z-index: 10;
}
.group:hover {
    z-index: 50;
}
  </style> 

  @vite(['resources/css/app.css', 'resources/js/app.js'])
 
</head>
<body class="bg-black text-white">

<div class="sticky top-0 z-50 flex items-center justify-between px-6 py-4 bg-black/80 backdrop-blur">

  <div class="flex items-center gap-6">
    <span class="text-red-600 font-bold text-xl">OTT</span>

    <a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a>
    <a href="{{ route('browse') }}" class="text-gray-300 hover:text-white">Browse</a>
    @auth
      @if(!auth()->user()->isConsumer())
        <a href="/admin/dashboard" class="text-gray-300 hover:text-white">Admin</a>
      @endif
    @endauth
    @auth
      @if(auth()->user()->isConsumer())
        <a href="{{ route('watchlist.index') }}">Watchlist</a>
      @endif
    @endauth
  </div>

  <form action="{{ route('search') }}" method="GET" class="relative">
    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Search movies, series..."
        class="bg-zinc-900 text-white px-4 py-2 rounded-lg
               focus:outline-none focus:ring-2 focus:ring-red-600"
    >
  </form>

</div>

@yield('content')

<footer class="border-t border-zinc-800 text-center py-8 text-gray-500 text-sm">
  © {{ date('Y') }} OTT Platform
</footer>

</body>

</html>
