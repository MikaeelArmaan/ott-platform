<form method="GET" class="flex flex-wrap gap-4 mb-6">

    <select name="type" class="bg-zinc-900 text-white px-3 py-2 rounded">
        <option value="">All Types</option>
        <option value="movie" @selected(request('type') == 'movie')>Movies</option>
        <option value="series" @selected(request('type') == 'series')>Series</option>
    </select>

    <select name="language" class="bg-zinc-900 text-white px-3 py-2 rounded">
        <option value="">All Languages</option>
        <option>English</option>
        <option>Hindi</option>
        <option>Spanish</option>
        <option>French</option>
    </select>

    <select name="rating" class="bg-zinc-900 text-white px-3 py-2 rounded">
        <option value="">All Ratings</option>
        <option>U</option>
        <option>U/A</option>
        <option>A</option>
    </select>

    <button class="bg-red-600 px-4 py-2 rounded font-semibold">
        Filter
    </button>

    <a href="{{ route('browse') }}" class="bg-gray-700 px-4 py-2 rounded font-semibold">
        Reset
    </a>

</form>
