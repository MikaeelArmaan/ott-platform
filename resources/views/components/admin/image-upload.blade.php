<div class="space-y-2">

    <label class="text-sm text-gray-400">{{ $label }}</label>

    <div
        onclick="document.getElementById('{{ $name }}').click()"
        class="cursor-pointer border-2 border-dashed border-zinc-700
               rounded-lg p-4 text-center
               hover:border-red-600 transition">

        <p class="text-gray-400 text-sm">
            Click or Drag Image Here
        </p>

        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="image/*"
            class="hidden"
            onchange="previewImage(event,'{{ $previewId }}')"
        >

        <img
            id="{{ $previewId }}"
            class="hidden mx-auto mt-3 h-32 rounded object-cover"
        >
    </div>

    @error($name)
        <p class="text-red-500 text-xs">{{ $message }}</p>
    @enderror

</div>
<script>
    function previewImage(event, targetId) {
        const input = event.target;
        const preview = document.getElementById(targetId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>