<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
</head>
<body>

    <main class="guest-container">
        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>