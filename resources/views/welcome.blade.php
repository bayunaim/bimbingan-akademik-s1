<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BimbinganSTOK - Bina Guna</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { 50: '#f0f7f9', 100: '#dceef2', 200: '#b0d9e2', 500: '#1e7b94', 600: '#145161', 700: '#0d3843' } } } }
        }
    </script>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,700,800&display=swap" rel="stylesheet" />
    <style> body { font-family: 'Figtree', sans-serif; } </style>
</head>
<body class="antialiased bg-slate-50 text-gray-800 selection:bg-primary-500 selection:text-white">

    <div class="relative min-h-screen flex flex-col justify-center items-center overflow-hidden">

        <div class="absolute inset-0 z-0">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            <div class="absolute top-40 -left-40 w-96 h-96 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl px-6 text-center">

            <div class="mx-auto mb-8 bg-white p-5 rounded-full inline-block shadow-xl border border-primary-50">
                <img src="{{ asset('img/logo.png') }}" class="w-24 h-24 filter drop-shadow-md" alt="Logo">
            </div>

            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-gray-900 mb-6 leading-tight">
                Sistem Bimbingan Akademik <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-500">STOK Bina Guna</span>
            </h1>

            <p class="text-lg md:text-xl text-gray-500 mb-10 max-w-2xl mx-auto font-medium leading-relaxed">
                Platform digital terpadu untuk mempermudah proses bimbingan akademik, tugas akhir, dan skripsi antara mahasiswa dan dosen pembimbing.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 text-lg font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-2xl shadow-lg hover:shadow-primary-500/30 transition transform hover:-translate-y-1">
                            Masuk ke Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-2xl shadow-lg hover:shadow-primary-500/30 transition transform hover:-translate-y-1">
                            Login Aplikasi
                        </a>
                    @endauth
                @endif
            </div>
        </div>

        <div class="absolute bottom-6 w-full text-center text-sm font-semibold text-gray-400 z-10">
            &copy; {{ date('Y') }} Program Studi Pendidikan Olahraga - STOK Bina Guna.
        </div>
    </div>

</body>
</html>
