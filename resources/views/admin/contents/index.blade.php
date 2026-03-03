@extends('admin.layout.app')

@section('title','Manage Contents')

@section('content')

<div class="container mx-auto p-6 text-white">

<h1 class="text-2xl font-semibold mb-6">🎬 Content Management</h1>


{{-- ================= CONTENT TABLE ================= --}}

<div class="bg-zinc-900 p-6 rounded-lg">

<h2 class="text-lg font-semibold mb-4">All Contents</h2>
<button
  onclick="openContentModal()"
  class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded font-semibold mb-4">
  ➕ Add Content
</button>
<div class="overflow-x-auto">

<table id="contentsTable" class="w-full text-sm">
<thead class="bg-zinc-800 text-gray-300">
<tr>
  <th class="p-3 text-left">ID</th>
  <th class="p-3">Poster</th>
  <th class="p-3 text-left">Title</th>
  <th class="p-3">Type</th>
  <th class="p-3">Rating</th>
  <th class="p-3">Published</th>
  <th class="p-3">Actions</th>
</tr>
</thead>

<tbody>

@foreach($contents as $c)
<tr class="border-b border-zinc-800 hover:bg-zinc-800">

<td class="p-3">{{ $c->id }}</td>

<td class="p-3">
<!-- @if($c->thumbnail_url)
<img src="{{ asset('storage/'.$c->thumbnail_url) }}" class="h-10 w-8 object-cover rounded">
@elseif($c->poster_url)
<img src="{{ asset('storage/'.$c->poster_url) }}" class="h-10 w-8 object-cover rounded">
@endif -->
<img src="{{ $c->thumbnail_url }}" class="h-10 w-8 object-cover rounded">
</td>

<td class="p-3 font-medium">{{ $c->title }}</td>

<td class="p-3 uppercase">{{ $c->type }}</td>

<td class="p-3">{{ $c->maturity_rating }}</td>

<td class="p-3">
@if($c->is_published)
<span class="text-green-400">Yes</span>
@else
<span class="text-red-400">No</span>
@endif
</td>
<td class="p-3 flex gap-2">

<!-- EDIT -->
<button
 class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs">
Edit
</button>

<!-- DELETE -->
<form method="POST"
      action="{{ route('admin.contents.destroy',$c->id) }}"
      onsubmit="return confirm('Delete this content?')">
    @csrf
    @method('DELETE')
    <button class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">
        Delete
    </button>
</form>

</td>
</tr>
@endforeach

</tbody>
</table>

</div>

</div>

</div>

{{-- ================= INPUT STYLES ================= --}}

<style>
.input{
  background:#181818;
  border:1px solid #333;
  padding:10px;
  border-radius:6px;
  color:white;
}
.input:focus{
  outline:none;
  border-color:#e50914;
}
</style>
<!-- ADD CONTENT MODAL -->
<div id="contentModal"
     class="fixed inset-0 hidden z-50">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/70"
         onclick="closeContentModal()"></div>

    <!-- Modal Box -->
    <div class="relative flex items-center justify-center min-h-screen p-4">

        <div class="bg-zinc-900 w-full max-w-3xl rounded-lg shadow-xl">

            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b border-zinc-800">
                <h2 class="text-lg font-semibold">Add New Content</h2>
                <button onclick="closeContentModal()"
                        class="text-gray-400 hover:text-white text-xl">
                    ✖
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 max-h-[80vh] overflow-y-auto">

                {{-- ERRORS --}}
                @if ($errors->any())
                    <div class="bg-red-900/40 border border-red-600 text-red-300 p-3 rounded mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORM --}}
                <form action="{{ route('admin.contents.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    @csrf

                    <select name="type" class="input" required>
                        <option value="">Select Type</option>
                        <option value="movie">Movie</option>
                        <option value="series">Series</option>
                    </select>

                    <x-admin.input name="title" label="Title" />

                    <x-admin.input name="language" label="Language" />

                    <x-admin.input name="release_date" label="Release Date" type="date" />

                    <x-admin.input name="runtime_seconds" label="Runtime (seconds)" type="number" />

                    <x-admin.input name="maturity_rating" label="Maturity Rating (U / U-A / A)" />

                    <x-admin.image-upload name="poster" label="Poster Image" />

                    <x-admin.image-upload name="thumbnail" label="Thumbnail Image" />

                    <x-admin.image-upload name="backdrop" label="Backdrop Image" />

                    <x-admin.video-upload name="video" label="Video File" />

                    <x-admin.textarea name="description" label="Description" />

                    <label class="flex items-center gap-2 md:col-span-3">
                        <input type="checkbox" name="is_published">
                        Publish Now
                    </label>

                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 px-6 py-2 rounded font-semibold md:col-span-3">
                        Save Content
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>

<script>
function openContentModal() {
    document.getElementById('contentModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeContentModal() {
    document.getElementById('contentModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

/* ESC key support */
document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
        closeContentModal();
    }
});
</script>

@if ($errors->any())
<script>
    openContentModal();
</script>
@endif
@endsection
