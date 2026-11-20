<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts.head')
</head>
<body>

    <header class="navbar">
        <div class="container">
            <a href="/" class="logo">
                OperinSini
            </a>

            <nav>
                @yield('navbar')
            </nav>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} OperinSini. All Rights Reserved.</p>
    </footer>

    @stack('scripts')

</body>
</html>