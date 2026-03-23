<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
</head>
<body class="font-sans bg-page text-ink">
    <div class="min-h-screen bg-page text-ink">
        @include('partials.home-sidebar')

        <div class="flex min-h-screen flex-col lg:pl-64">
            @include('partials.home-header')

            <main class="flex-1">
                @yield('content')
            </main>

            @include('partials.home-footer')
        </div>
    </div>

    <script>
        (function () {
            const openButton = document.getElementById('open-sidebar');
            const closeButton = document.getElementById('close-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const sidebar = document.getElementById('mobile-sidebar');

            if (!openButton || !closeButton || !backdrop || !sidebar) {
                return;
            }

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openButton.addEventListener('click', openSidebar);
            closeButton.addEventListener('click', closeSidebar);
            backdrop.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>
