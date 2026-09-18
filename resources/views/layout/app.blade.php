<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PintarKuy - Bimbel Adaptif UTBK-SNBT')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..900&family=Manrope:wght@300..800&display=swap" rel="stylesheet">

    {{-- Compiled CSS & JS from resources/css/app.css and resources/js/app.js via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.pintarKuyAuthed = @json(auth()->check());
    </script>

    <script>
        // Helper auth front-end - didefinisikan di sini (eksekusi sinkron) supaya
        // script layout (yang jalan saat parse) sudah bisa memakai window.pintarKuyAuth.
        window.pintarKuyAuth = {
            user() {
                try { return JSON.parse(localStorage.getItem('pintarKuyUser') || 'null'); } catch (e) { return null; }
            },
            isLoggedIn() { return !!this.user(); },
            login(u) {
                const prev = this.user() || {};
                const next = Object.assign({ name: '' }, prev, u || {});
                delete next.photo;
                try { localStorage.setItem('pintarKuyUser', JSON.stringify(next)); } catch (e) {}
            },
            logout() { try { localStorage.removeItem('pintarKuyUser'); } catch (e) {} },
        };
    </script>

    @stack('styles')
</head>
<body class="text-ink bg-paper antialiased">
    {{ $slot ?? '' }}
    @yield('content')

    @stack('scripts')
</body>
</html>
