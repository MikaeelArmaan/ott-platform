<div
    x-cloak
    x-data="contentWizard(
        '{{ $content->type ?? 'movie' }}',
        {{ json_encode($seasonsJson ?? []) }}
    )"
    class="space-y-6">

    @include('admin.contents.wizard.steps')

    <!-- STEP CONTENT -->

    <div x-show="step===1">
        @include('admin.contents.sections.content-info')
    </div>

    <div x-show="step===2">
        @include('admin.contents.sections.media')
    </div>

    <div x-show="step===3 && type==='movie'">
        @include('admin.contents.sections.video')
    </div>

    <div x-show="step===3 && type==='series'">
        @include('admin.contents.sections.seasons')
    </div>

    <div x-show="step===4">
        @include('admin.contents.sections.publish')
    </div>

    @include('admin.contents.wizard.navigation')

</div>