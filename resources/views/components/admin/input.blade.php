<div class="relative">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name) }}"
        placeholder=" "
        class="peer w-full bg-zinc-900 border border-zinc-700 rounded
               px-3 pt-5 pb-2 text-white
               focus:border-red-600 focus:outline-none"
    >

    <label
        for="{{ $name }}"
        class="absolute left-3 top-2 text-gray-400 text-xs transition-all
               peer-placeholder-shown:top-3.5
               peer-placeholder-shown:text-sm
               peer-placeholder-shown:text-gray-500
               peer-focus:top-2
               peer-focus:text-xs
               peer-focus:text-red-500">
        {{ $label }}
    </label>

    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
