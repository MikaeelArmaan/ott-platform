@extends('admin.layouts.app')

@section('title', 'Manage Contents')

@section('content')

<div class="container mx-auto p-6 text-white contentManagement">

    <h1 class="text-2xl font-semibold mb-6">
        🎬 Content Management
    </h1>


    <div class="bg-zinc-900 p-6 rounded-lg">


        <div class="flex justify-between mb-6">

            <h2 class="text-lg font-semibold">
                All Contents
            </h2>

            <a href="{{ route('admin.contents.create') }}"
                class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded font-semibold">
                ➕ Add Content
            </a>

        </div>


        <div class="overflow-x-auto">

            <table id="contentsTable" class="w-full text-sm">

                <thead class="bg-zinc-800 text-gray-300">

                    <tr>

                        <th class="p-3 text-left">ID</th>

                        <th class="p-3">Poster</th>

                        <th class="p-3 text-left">Title</th>

                        <th class="p-3">Type</th>

                        <th class="p-3">Seasons</th>

                        <th class="p-3">Episodes</th>

                        <th class="p-3">Rating</th>

                        <th class="p-3">Published</th>

                        <th class="p-3">Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($contents as $c)

                    <tr class="border-b border-zinc-800 hover:bg-zinc-800">

                        <td class="p-3">
                            {{ $c->id }}
                        </td>


                        <td class="p-3">

                            @if ($c->thumbnail_url)

                            <img src="{{ asset('storage/'.$c->thumbnail_url) }}"
                                class="h-12 w-9 object-cover rounded">

                            @elseif($c->poster_url)

                            <img src="{{ asset('storage/'.$c->poster_url) }}"
                                class="h-12 w-9 object-cover rounded">

                            @else

                            <div class="h-12 w-9 bg-zinc-700 rounded"></div>

                            @endif

                        </td>


                        <td class="p-3 font-medium">

                            {{ $c->title }}

                        </td>


                        <td class="p-3 uppercase">

                            <span class="badge">

                                {{ $c->type }}

                            </span>

                        </td>


                        <td class="p-3">

                            {{ $c->seasons_count ?? 0 }}

                        </td>


                        <td class="p-3">

                            {{ $c->episodes_count ?? 0 }}

                        </td>


                        <td class="p-3">

                            {{ $c->maturity_rating }}

                        </td>


                        <td class="p-3">

                            <div class="relative inline-block w-11 h-5">

                                <input
                                    type="checkbox"
                                    id="publish-{{ $c->id }}"
                                    class="publish-toggle peer appearance-none w-11 h-5  rounded-full checked:bg-green-600 cursor-pointer transition-colors duration-300"
                                    data-id="{{ $c->id }}"
                                    {{ $c->is_published ? 'checked' : '' }}>

                                <label
                                    for="publish-{{ $c->id }}"
                                    class="absolute top-0 left-0 w-5 h-5 bg-white rounded-full border border-slate-300 shadow-sm transition-transform duration-300 peer-checked:translate-x-6 cursor-pointer">
                                </label>

                            </div>

                        </td>


                        <td class="p-3 flex gap-2">

                            <a href="{{ route('admin.contents.edit', $c->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs">
                                Edit
                            </a>


                            <form method="POST"
                                action="{{ route('admin.contents.destroy', $c->id) }}"
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

@endsection