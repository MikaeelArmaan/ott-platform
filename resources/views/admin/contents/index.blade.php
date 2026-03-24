@extends('admin.layouts.app')

@section('title', 'Manage Contents')

@section('content')

<div class="container mx-auto p-6 text-white contentManagement">

    <h1 class="text-2xl font-semibold mb-6">
        🎬 Content Management
    </h1>


    <div class="bg-zinc-900 p-6 rounded-lg">


        <div class="flex flex-wrap gap-3 justify-between items-center mb-6">

            <h2 class="text-lg font-semibold">
                All Contents
            </h2>
            <x-admin.button href="{{ route('admin.contents.create') }}">
                Add Content
            </x-admin.button>
            <!-- <a href="{{ route('admin.contents.create') }}"
                class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded font-semibold">
                ➕ Add Content
            </a> -->

        </div>


        <div class="overflow-x-auto">

            <x-admin.datatable id="contentsTable">

                <x-slot name="head">

                    <tr>

                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 w-16">Poster</th>
                        <th class="p-3 text-left">Title</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Seasons</th>
                        <th class="p-3">Episodes</th>
                        <th class="p-3">Rating</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Published</th>
                        <th class="p-3">Featured</th>
                        <th class="p-3">Trending</th>
                        <th class="p-3">Recommended</th>
                        <th class="p-3">Actions</th>

                    </tr>

                </x-slot>


                <x-slot name="foot">

                    <tr>

                        <th>ID</th>
                        <th></th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Seasons</th>
                        <th>Episodes</th>
                        <th>Rating</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>

                </x-slot>


                @foreach ($contents as $c)

                <tr class="hover:bg-zinc-800">

                    <td class="p-3">{{ $c->id }}</td>

                    <td class="p-3">
                        @if ($c->thumbnail_url)
                        <img src="{{ media_url($c->thumbnail_url) }}" class="h-12 w-9 object-cover rounded shadow">
                        @elseif($c->poster_url)
                        <img src="{{ media_url($c->poster_url) }}" class="h-12 w-9 object-cover rounded shadow">
                        @else
                        <div class="h-12 w-9 bg-zinc-700 rounded"></div>
                        @endif
                    </td>

                    <td class="p-3 font-medium">{{ $c->title }}</td>

                    <td class="p-3 uppercase">
                        <span class="px-2 py-1 text-xs rounded-full bg-zinc-700">
                            {{ $c->type }}
                        </span>
                    </td>

                    <td class="p-3">{{ $c->seasons_count ?? 0 }}</td>
                    <td class="p-3">{{ $c->episodes_count ?? 0 }}</td>
                    <td class="p-3">{{ $c->maturity_rating }}</td>

                    {{-- STATUS --}}
                    <td class="p-3">
                        @if($c->videoAsset && $c->videoAsset->status === 'ready')
                        <span class="text-green-400 text-xs">Ready</span>
                        @elseif($c->videoAsset && $c->videoAsset->status === 'processing')
                        <span class="text-yellow-400 text-xs">Processing</span>
                        @else
                        <span class="text-zinc-500 text-xs">No Video</span>
                        @endif
                    </td>

                    {{-- PUBLISHED --}}
                    <td class="p-3">
                        <x-admin.toggle
                            :checked="$c->is_published"
                            :id="$c->id"
                            field="is_published"
                            variant="published" />
                    </td>

                    {{-- FEATURED --}}
                    <td class="p-3">
                        <x-admin.toggle
                            :checked="$c->is_featured"
                            :id="$c->id"
                            field="is_featured"
                            variant="featured" />
                    </td>

                    {{-- TRENDING --}}
                    <td class="p-3">
                        <x-admin.toggle
                            :checked="$c->is_trending"
                            :id="$c->id"
                            field="is_trending"
                            variant="trending" />
                    </td>

                    {{-- RECOMMENDED --}}
                    <td class="p-3">
                        <x-admin.toggle
                            :checked="$c->is_recommended"
                            :id="$c->id"
                            field="is_recommended"
                            variant="recommended" />
                    </td>
                    {{-- ACTIONS --}}
                    <td class="p-3">
                        <x-admin.actions
                            :preview="route('title.show',$c->slug)"
                            :edit="route('admin.contents.edit',$c->id)"
                            :delete="route('admin.contents.destroy',$c->id)" />
                    </td>

                </tr>

                @endforeach

            </x-admin.datatable>

        </div>

    </div>

</div>

@endsection