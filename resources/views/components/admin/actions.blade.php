@props([
'edit' => null,
'delete' => null,
'preview' => null
])

<div class="flex gap-3 items-center justify-center">

    {{-- Preview --}}
    @if($preview)
    <a href="{{ $preview }}"
        target="_blank"
        class="text-green-400 hover:text-green-300"
        title="Preview">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5
                     12 5c4.478 0 8.268 2.943
                     9.542 7-1.274 4.057-5.064
                     7-9.542 7-4.477 0-8.268-2.943
                     -9.542-7z" />
        </svg>
    </a>
    @endif


    {{-- Edit --}}
    @if($edit)
    <a href="{{ $edit }}"
        class="text-blue-400 hover:text-blue-300"
        title="Edit">

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11
                     a2 2 0 002 2h11a2 2 0
                     002-2v-5M18.5 2.5a2.121
                     2.121 0 113 3L12 15l-4 1
                     1-4 9.5-9.5z" />
        </svg>

    </a>
    @endif


    {{-- Delete --}}
    @if($delete)
    <form method="POST" action="{{ $delete }}">
        @csrf
        @method('DELETE')
        <button
            type="button"
            class="delete-btn bg-transparent text-red-500 hover:text-red-400 p-2 rounded hover:bg-zinc-800 transition"
            title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
             a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
             M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16" />
            </svg>
        </button>
    </form>

    @endif

</div>