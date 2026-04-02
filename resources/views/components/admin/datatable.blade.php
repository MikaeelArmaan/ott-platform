<style>
    /* .datatable-filter {
        width: 100%;
        max-width: 140px;
        background: #18181b;
        border: 1px solid #3f3f46;
        color: white;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 12px;
    } */

    /* PAGINATION CONTAINER */
    .dt-paging {
        display: flex !important;
        justify-content: center;
        align-items: center;
        gap: 6px;
        margin-top: 16px;
    }

    /* BUTTON BASE */
    .dt-paging-button {
        padding: 6px 12px !important;
        border-radius: 6px;
        background: #27272a !important;
        color: #fff !important;
        border: 1px solid #3f3f46 !important;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    /* HOVER */
    .dt-paging-button:hover {
        background: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #fff !important;
    }

    /* ACTIVE */
    .dt-paging-button.current {
        background: #dc2626 !important;
        border-color: #dc2626 !important;
        color: #fff !important;
    }

    /* DISABLED */
    .dt-paging-button.disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* REMOVE DEFAULT SPACING */
    .dt-paging-button+.dt-paging-button {
        margin-left: 0 !important;
    }

    .dt-paging-button {
        border-radius: 999px;
        margin: 0 5px;
    }

    .dt-info {
        text-align: left;
        color: #a1a1aa;
        margin-top: 10px;
    }

    /* TOP BAR */
    .dt-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    /* ENTRIES DROPDOWN */
    .dt-length select {
        background: #18181b;
        border: 1px solid #3f3f46;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
    }

    /* LABEL TEXT */
    .dt-length label {
        color: #a1a1aa;
        font-size: 13px;
    }

    /* SEARCH CONTAINER */
    .dt-search {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* SEARCH LABEL */
    .dt-search label {
        color: #a1a1aa;
        font-size: 13px;
    }

    /* SEARCH INPUT */
    .dt-search input {
        background: #18181b;
        border: 1px solid #dc2626;
        color: white;
        padding: 6px 10px;
        border-radius: 6px;
        outline: none;
    }

    /* FOCUS */
    .dt-search input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 1px #dc2626;
    }

    .dt-search label {
        display: none;
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
        {{-- BODY --}}
        <tbody class="divide-y divide-zinc-800">
            {{ $slot }}
        </tbody>
        {{-- FOOT --}}
        @if(isset($foot))
        <tfoot class="bg-zinc-900">
            {{ $foot }}
        </tfoot>
        @endif

    </table>

</div>