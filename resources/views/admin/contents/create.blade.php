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

        <div
            x-data="contentWizard(
            '{{ $content->type ?? 'movie' }}',
            window.initialSeasons || []
        )"
            x-cloak
            class="space-y-6">

            <form
                action="{{ route('admin.contents.store') }}"
                method="POST"
                enctype="multipart/form-data"
                @submit.prevent="submitForm">

                @csrf

                @include('admin.contents._form')

            </form>

        </div>

    </x-admin.card>
</div>

@endsection