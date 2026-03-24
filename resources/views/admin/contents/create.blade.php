@extends('admin.layouts.app')

@section('title', 'Add Content')

@section('content')
<script>
    window.initialSeasons = @json($seasonsJson ?? []);
</script>
<div class="max-w-full mx-auto p-6 text-white">

    <x-admin.page-header
        title="Add Content"
        description="Create a new movie or series for the OTT platform"
        :breadcrumb="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Contents', 'url' => route('admin.contents.index')],
        ['label' => 'Add Content']
    ]" />

    <x-admin.card>

        @include('admin.notifications.form_errors')

        <form
            action="{{ route('admin.contents.store') }}"
            method="POST"
            enctype="multipart/form-data"
            onsubmit="NProgress.start()">

            @csrf

            @include('admin.contents._form')

        </form>

    </x-admin.card>

</div>

@endsection