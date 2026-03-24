@props(['color' => 'gray'])

@php
$colors = [
'gray' => 'bg-zinc-700 text-white',
'green' => 'bg-green-600 text-white',
'red' => 'bg-red-600 text-white'
];
@endphp

<span class="px-2 py-1 text-xs rounded {{ $colors[$color] }}">
    {{ $slot }}
</span>