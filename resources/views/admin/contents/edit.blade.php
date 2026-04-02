@extends('admin.layouts.app')

@section('title', 'Edit Content')

@section('content')
<!-- <script>
    window.initialSeasons = @json($seasonsJson ?? []);
</script> -->
<div class="container mx-auto p-6 text-white">

    <h1 class="text-2xl font-semibold mb-6">Edit Content</h1>

    <div class="bg-zinc-900 p-6 rounded-lg" 
        x-cloak
        x-ref="root"
        x-data="contentWizard(
        '{{ $content->type ?? 'movie' }}',
        @js($seasonsJson ?? []))"
        >

        <form
            action="{{ route('admin.contents.update', $content->id) }}"
            method="POST"
            enctype="multipart/form-data"
            @submit.prevent="submitForm"
            >
            @csrf
            @method('PUT')

            @include('admin.contents._form')

            <button class="bg-red-600 px-6 py-2 rounded mt-4">
                Update Content
            </button>

        </form>

    </div>

</div>

@endsection