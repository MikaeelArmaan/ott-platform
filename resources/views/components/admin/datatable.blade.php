<style>
    .datatable-filter {
        width: 100%;
        max-width: 140px;
        background: #18181b;
        border: 1px solid #3f3f46;
        color: white;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 12px;
    }
</style>
@props([
'id' => 'datatable',
'pageLength' => 10,
'orderColumn' => 0,
'orderDir' => 'asc'
])

<div class="overflow-x-auto">

    <table
        id="{{ $id }}"
        class="datatable w-full text-sm text-left "
        data-page-length="{{ $pageLength }}"
        data-order-column="{{ $orderColumn }}"
        data-order-dir="{{ $orderDir }}">

        {{-- HEAD --}}
        @if(isset($head))
        <thead class="bg-zinc-800 text-gray-300">
            {{ $head }}
        </thead>
        @endif


        {{-- FOOT --}}
        @if(isset($foot))
        <tfoot class="bg-zinc-900">
            {{ $foot }}
        </tfoot>
        @endif


        {{-- BODY --}}
        <tbody class="divide-y divide-zinc-800">
            {{ $slot }}
        </tbody>

    </table>

</div>