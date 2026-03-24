<!DOCTYPE html>
<html lang="en">

@include('admin.partials.head')

<body class="bg-zinc-950 text-white ">

    <div class="flex min-h-screen w-full">

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')


        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col">

            {{-- MOBILE HEADER --}}
            <header class="lg:hidden flex items-center justify-between p-4 bg-zinc-900 border-b border-zinc-800">

                <button id="sidebarToggle" class="text-xl">
                    ☰
                </button>

                <h1 class="font-semibold">
                    OTT Admin
                </h1>

            </header>


            {{-- PAGE CONTENT --}}
            <main class="flex-1 p-4 lg:p-8">
                @include('admin.notifications.form_errors')
                @yield('content')

            </main>

        </div>

    </div>

    @include('admin.partials.scripts')

    @stack('scripts')
    <div id="global-loader" class="hidden fixed inset-0 z-[9999] flex items-center justify-center backdrop-blur-md bg-black/40">
        <div class="spinner"></div>
</body>

</html>