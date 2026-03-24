@props([
'name',
'label',
'previewId',
'value' => null
])

<div class="space-y-2">

    <label class="text-sm text-gray-400">{{ $label }}</label>

    <div
        class="image-upload cursor-pointer border-2 border-dashed border-zinc-700
        rounded-lg p-4 text-center hover:border-red-600 transition"
        data-input="{{ $name }}"
        data-preview="{{ $previewId }}">

        <p class="text-gray-400 text-sm">
            Click or Drag Image Here
        </p>

        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="image/*"
            class="hidden"
            onchange="previewImage(event,'{{ $previewId }}')" />

        <img
            id="{{ $previewId }}"
            src="{{ media_url($value) }}"
            class="mx-auto mt-3 h-32 rounded object-cover {{ empty($value) ? 'hidden' : '' }}">
    </div>

    @error($name)
    <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror

</div>