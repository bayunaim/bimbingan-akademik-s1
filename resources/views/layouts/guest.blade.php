<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BimbinganSTOK') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: { 50: '#f0f7f9', 100: '#dceef2', 500: '#1e7b94', 600: '#145161', 700: '#0d3843' } } } }
        }
    </script>
    <style> body { font-family: 'Figtree', sans-serif; } </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-primary-600 relative overflow-hidden selection:bg-primary-500 selection:text-white min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-32 -left-32 w-[30rem] h-[30rem] bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -right-20 w-[40rem] h-[40rem] bg-white opacity-5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-3xl border border-gray-100">

        <div class="flex justify-center mb-6">
            <div class="bg-primary-50 w-20 h-20 rounded-full flex items-center justify-center border-2 border-primary-100 shadow-inner">
                <img src="{{ asset('img/logo.png') }}" class="w-12 h-12" alt="Logo">
            </div>
        </div>

        {{ $slot }}

    </div>

    <div class="relative z-10 mt-8 text-center text-primary-100 text-sm font-semibold tracking-wide">
        &copy; {{ date('Y') }} STOK Bina Guna
    </div>
</body>
</html>
