<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BimbinganSTOK') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:600,700,800|plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['Sora', 'sans-serif']
                    },
                    colors: {
                        primary: {
                            50: '#f3faf5',
                            100: '#dff3e6',
                            200: '#bde4cb',
                            300: '#8fcfa6',
                            400: '#5bb47d',
                            500: '#2f9a5a',
                            600: '#1f7a44',
                            700: '#18623a',
                            800: '#124c2e',
                            900: '#0c3420'
                        },
                        gold: {
                            400: '#f2c230',
                            500: '#e0a912'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1,
        h2,
        h3 {
            font-family: 'Sora', sans-serif;
            letter-spacing: -0.01em;
        }

        .track-lanes {
            background-image: repeating-linear-gradient(112deg, transparent 0 54px, rgba(255, 255, 255, .06) 54px 56px);
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-white selection:bg-primary-200">

    <div class="min-h-screen lg:grid lg:grid-cols-[1.05fr_1fr]">

        {{-- Panel kiri: lintasan lari sebagai motif --}}
        <aside
            class="track-lanes relative hidden lg:flex flex-col justify-between bg-primary-800 text-white p-14 overflow-hidden">
            <div class="absolute inset-y-0 right-0 w-2 bg-gold-400"></div>

            <div class="flex items-center gap-4">
                <div class="bg-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg">
                    <img src="{{ asset('img/logo.png') }}" class="w-9 h-9" alt="Logo STOK Bina Guna">
                </div>
                <p class="font-display font-bold text-lg leading-tight">STOK Bina Guna</p>
            </div>

            <div class="max-w-md">
                <h1 class="font-display text-4xl font-extrabold leading-tight">Bimbingan akademik, tercatat rapi dari
                    awal sampai pengesahan.</h1>
                <p class="mt-5 text-primary-100 leading-relaxed">Ajukan, review, dan cetak lembar bimbingan untuk
                    mahasiswa IKOR, PJKR, dan PKH di satu tempat.</p>
            </div>

            <p class="text-sm text-primary-200">&copy; {{ date('Y') }} STOK Bina Guna</p>
        </aside>

        {{-- Panel kanan: form --}}
        <main class="flex flex-col justify-center items-center px-6 py-12 sm:px-12">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <img src="{{ asset('img/logo.png') }}" class="w-11 h-11" alt="Logo">
                    <p class="font-display font-bold text-primary-800">STOK Bina Guna</p>
                </div>

                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
