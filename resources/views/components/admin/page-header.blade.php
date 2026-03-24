@props([
'title',
'description' => null,
'breadcrumb' => [],
'actions' => null
])

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

    <div>

        @if(!empty($breadcrumb))
        @include('admin.partials.breadcrumb', ['items' => $breadcrumb])
        @endif

        <h1 class="text-2xl font-semibold">
            {{ $title }}
        </h1>

        @if($description)
        <p class="text-sm text-zinc-400 mt-1">
            {{ $description }}
        </p>
        @endif

    </div>

    @if($actions)
    <div class="flex gap-3">
        {!! $actions !!}
    </div>
    @endif

</div>