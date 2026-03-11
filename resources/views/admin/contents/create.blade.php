@extends('admin.layout.app')

@section('title', 'Add Content')

@section('content')

    <div class="container mx-auto p-6 text-white">

        <h1 class="text-2xl font-semibold mb-6">Add Content</h1>

        <div class="bg-zinc-900 p-6 rounded-lg">

            <form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                @include('admin.contents._form')

                <button class="bg-red-600 px-6 py-2 rounded mt-4">
                    Save Content
                </button>

            </form>

        </div>

    </div>

@endsection
