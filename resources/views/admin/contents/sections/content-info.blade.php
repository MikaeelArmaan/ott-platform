<x-admin.card>

    <h2 class="text-lg font-semibold mb-4">
        Content Information
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- GENRES (IMPORTANT) --}}
        <x-admin.select
            name="type"
            label="Type"
            select2
            :options="['movie' => 'Movie', 'series' => 'Series']" />
        {{-- TYPE --}}
        <!-- <x-admin.select name="type" label="Type">
            <option value="movie"
                {{ old('type', optional($content)->type) == 'movie' ? 'selected' : '' }}>
                Movie
            </option>

            <option value="series"
                {{ old('type', optional($content)->type) == 'series' ? 'selected' : '' }}>
                Series
            </option>
        </x-admin.select> -->
        <x-admin.select
            name="genres[]"
            label="Genres"
            :options="$genres->pluck('name','id')->toArray()"
            :value="$content->genres->pluck('id')->toArray() ?? []"
            select2 />
        {{-- TITLE --}}
        <x-admin.input
            name="title"
            label="Title"
            :value="old('title', optional($content)->title)" />

        {{-- LANGUAGE --}}
        <x-admin.input
            name="language"
            label="Language"
            :value="old('language', optional($content)->language)" />

        {{-- RELEASE DATE --}}
        <x-admin.input
            name="release_date"
            label="Release Date"
            type="date"
            :value="old('release_date', optional($content->release_date)->format('Y-m-d'))" />

        {{-- RUNTIME (AUTO) --}}
        <x-admin.input
            name="runtime_seconds"
            label="Runtime (Auto)"
            readonly
            :value="optional($content)->runtime_seconds" />

        {{-- MATURITY --}}
        <x-admin.input
            name="maturity_rating"
            label="Rating"
            :value="old('maturity_rating', optional($content)->maturity_rating)" />

    </div>


</x-admin.card>