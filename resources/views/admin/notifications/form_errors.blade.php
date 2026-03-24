@if ($errors->any())

<div class="bg-red-600/20 border border-red-600 text-red-400 p-4 rounded mb-6">

    <div class="font-semibold mb-2">
        Please fix the following errors:
    </div>

    <ul class="list-disc pl-5 space-y-1 text-sm">

        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach

    </ul>

</div>

@endif