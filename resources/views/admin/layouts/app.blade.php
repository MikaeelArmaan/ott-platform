<!DOCTYPE html>
<html lang="en">

@include('admin.partials.head')

<body>

    @include('admin.partials.sidebar')

    <div class="content">
        @yield('content')
    </div>

    @include('admin.partials.scripts')

</body>

</html>