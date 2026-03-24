@props([
'href' => null,
'type' => 'primary'
])

@if($href)
<a href="{{ $href }}"
    {{ $attributes->merge([
   'class' => 'bg-red-600 hover:bg-red-700 px-5 py-2 rounded font-semibold inline-flex items-center gap-2'
   ]) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge([
   'class' => 'bg-red-600 hover:bg-red-700 px-5 py-2 rounded font-semibold'
]) }}>
    {{ $slot }}
</button>
@endif