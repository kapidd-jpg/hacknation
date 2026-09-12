<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StudyServer — Bimbel Adaptif UTBK-SNBT')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind via CDN — no build step / no npm dependency needed.
         Swap this for resources/css/app.css + Vite when you're ready for a production build. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        navy: {
                            DEFAULT: '#111c4e',
                            950: '#00052c',
                            900: '#0b1c30',
                            800: '#111c4e',
                            600: '#515b90',
                            400: '#7b85bd',
                            300: '#b9c3ff',
                            100: '#dee1ff',
                            50: '#eff4ff',
                        },
                        brand: {
                            green: '#006d30',
                            greenlight: '#7efc9a',
                            greentext: '#007433',
                        },
                        ink: { DEFAULT: '#0b1c30', soft: '#45464f', muted: '#767680' },
                    },
                    boxShadow: {
                        card: '0px 4px 20px -2px rgba(15,27,76,0.06)',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; background:#f8f9ff; }
    </style>

    @stack('styles')
</head>
<body class="text-ink antialiased">
    {{ $slot ?? '' }}
    @yield('content')

    @stack('scripts')
</body>
</html>
