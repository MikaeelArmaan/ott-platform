<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <select name="type" class="input">
        <option value="movie">Movie</option>
        <option value="series">Series</option>
    </select>

    <x-admin.input name="title" label="Title" :value="$content->title ?? ''" />

    <x-admin.input name="language" label="Language" />

    <x-admin.input name="release_date" label="Release Date" type="date" />

    <x-admin.input name="runtime_seconds" label="Runtime" />

    <x-admin.input name="maturity_rating" label="Rating" />

    <x-admin.image-upload name="poster" label="Poster" />

    <x-admin.image-upload name="thumbnail" label="Thumbnail" />

    <x-admin.image-upload name="backdrop" label="Backdrop" />

    <x-admin.video-upload name="video" label="Video" />

    <x-admin.textarea name="description" label="Description" />

    <label class="flex items-center gap-2 md:col-span-3">
        <input type="checkbox" name="is_published">
        Publish
    </label>

</div>
