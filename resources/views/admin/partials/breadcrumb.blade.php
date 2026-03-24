@props(['items' => []])

<nav class="text-sm text-zinc-400 mb-4 flex items-center gap-2">

    @foreach($items as $item)

    @if(!$loop->last)

    <a href="{{ $item['url'] }}"
        class="hover:text-white transition">
        {{ $item['label'] }}
    </a>

    <span class="text-zinc-600">/</span>

    @else

    <span class="text-white font-medium">
        {{ $item['label'] }}
    </span>

    @endif

    @endforeach

</nav>