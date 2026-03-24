<x-admin.card>

    <h2 class="text-lg font-semibold mb-4">
        Publishing
    </h2>

    <label class="flex items-center justify-between cursor-pointer">

        <span class="text-sm text-gray-300">
            Publish Content
        </span>

        <div class="relative">
            <input
                type="checkbox"
                name="is_published"
                class="sr-only peer"
                {{ old('is_published', $content->is_published ?? false) ? 'checked' : '' }}
            >

            <!-- Track -->
            <div class="w-12 h-6 bg-zinc-700 rounded-full peer
                        peer-checked:bg-red-600
                        transition-all"></div>

            <!-- Thumb -->
            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full
                        transition-all
                        peer-checked:translate-x-6"></div>
        </div>

    </label>

</x-admin.card>