@props([
'checked' => false,
'id' => null,
'field' => null,
'variant' => 'default'
])

<label class="relative inline-flex items-center cursor-pointer">

    <input
        type="checkbox"
        class="sr-only peer content-toggle"
        data-id="{{ $id }}"
        data-field="{{ $field }}"
        {{ $checked ? 'checked' : '' }}>

    {{-- TRACK --}}
    <div class="
        w-9 h-4 rounded-full bg-red-500
        transition-all duration-200 relative

        @if($variant === 'published') peer-checked:bg-green-500 @endif
        @if($variant === 'featured') peer-checked:bg-violet-500 @endif
        @if($variant === 'trending') peer-checked:bg-orange-500 @endif
        @if($variant === 'recommended') peer-checked:bg-blue-500 @endif
    ">

        {{-- LOADER --}}
        <span class="loader hidden absolute right-1 top-1 w-2 h-2 bg-white rounded-full animate-pulse"></span>

    </div>

    {{-- KNOB --}}
    <div class="absolute left-1 
        w-2 h-2 bg-white rounded-full
        transition-transform duration-200
        peer-checked:translate-x-5">
    </div>

</label>