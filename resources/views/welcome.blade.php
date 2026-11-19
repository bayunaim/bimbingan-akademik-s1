<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BimbinganSTOK - Bina Guna</title>

    <script src="https://cdn.tailwindcss.com"></script>
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
    <link href="https://fonts.bunny.net/css?family=sora:600,700,800|plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-slate-50 text-gray-800 selection:bg-primary-500 selection:text-white">

    <div class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden">

        <div class="absolute inset-0 z-0">
            <div
                class="absolute -top-40 -right-40 w-96 h-96 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70">
            </div>
            <div
                class="absolute top-40 -left-40 w-96 h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70">
            </div>
        </div>

        <div class="relative z-10 w-full max-w-4xl px-6 text-center">

            <div class="mx-auto mb-8 bg-white p-5 rounded-full inline-block shadow-xl border border-primary-50">
                <img src="{{ asset('img/logo.png') }}" class="w-24 h-24 filter drop-shadow-md" alt="Logo">
            </div>

            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                Sistem Bimbingan Akademik <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-500">STOK Bina
                    Guna</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-500 mb-10 max-w-2xl mx-auto font-medium leading-relaxed">
                Platform digital terpadu untuk mempermudah proses bimbingan akademik, tugas akhir, dan skripsi antara
                mahasiswa dan dosen pembimbing.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-8 py-4 text-lg font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-2xl shadow-lg hover:shadow-primary-500/30 transition transform hover:-translate-y-1">
                            Masuk ke Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-8 py-4 text-lg font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-2xl shadow-lg hover:shadow-primary-500/30 transition transform hover:-translate-y-1">
                            Login Aplikasi
                        </a>
                    @endauth
                @endif
            </div>
        </div>

        <div class="absolute bottom-6 w-full text-center text-sm font-semibold text-gray-400 z-10">
            &copy; {{ date('Y') }} STOK Bina Guna.
        </div>
    </div>

</body>

</html>
