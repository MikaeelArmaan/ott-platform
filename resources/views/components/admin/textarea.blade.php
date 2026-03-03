<div class="relative">
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="3"
        class="peer w-full bg-zinc-900 border border-zinc-700 rounded
               px-3 pt-5 pb-2 text-white
               focus:border-red-600 focus:outline-none"
        placeholder=" "
    >{{ old($name) }}</textarea>

    <label
        for="{{ $name }}"
        class="absolute left-3 top-2 text-xs text-gray-400
               peer-placeholder-shown:top-4
               peer-placeholder-shown:text-sm
               peer-focus:top-2
               peer-focus:text-xs
               peer-focus:text-red-500
               transition-all"
    >
        {{ $label }}
    </label>
</div>
