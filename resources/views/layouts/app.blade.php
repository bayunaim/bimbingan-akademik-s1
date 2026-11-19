<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'STOK Binaguna') }}</title>

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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased text-gray-800 bg-primary-50/40">

    <div x-data="{ sidebarOpen: false, sidebarMini: false }" class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900 bg-opacity-50 lg:hidden"
            @click="sidebarOpen = false"></div>

        <div :class="[
            sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in',
            sidebarMini ? 'lg:w-20' : 'lg:w-64'
        ]"
            class="fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 transform bg-white border-r border-primary-100 lg:translate-x-0 lg:static w-64">

            <div class="flex items-center justify-center mt-6 pb-6 border-b border-primary-100 mx-4 h-16">
                <div class="flex items-center w-full" :class="sidebarMini ? 'justify-center' : 'justify-start px-2'">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo"
                        class="w-9 h-9 flex-shrink-0 transition-transform duration-300 hover:scale-110">

                    <span x-show="!sidebarMini" x-transition.opacity
                        class="ml-3 text-lg font-display font-extrabold text-primary-800 tracking-tight whitespace-nowrap overflow-hidden">
                        Bimbingan<span class="text-primary-500">STOK</span>
                    </span>
                </div>
            </div>

            <nav class="mt-6 px-3 space-y-2 flex-1 overflow-y-auto overflow-x-hidden scrollbar-hide">

                <a title="Dashboard" :class="sidebarMini ? 'justify-center' : 'justify-start px-4'"
                    class="flex items-center py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}"
                    href="{{ route('dashboard') }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span x-show="!sidebarMini" class="ml-3 font-medium text-sm whitespace-nowrap">Dashboard</span>
                </a>

                <a title="Data Bimbingan" :class="sidebarMini ? 'justify-center' : 'justify-start px-4'"
                    class="flex items-center py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('bimbingan.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}"
                    href="{{ route('bimbingan.index') }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span x-show="!sidebarMini" class="ml-3 font-medium text-sm whitespace-nowrap">Data Bimbingan</span>
                </a>

                @if (in_array(auth()->user()->role, ['admin', 'kaprodi']))
                    <div class="pt-4 mt-4 border-t border-primary-100">
                        <p x-show="!sidebarMini"
                            class="px-4 text-[11px] font-bold tracking-wider text-gray-400 uppercase mb-2 whitespace-nowrap">
                            Master Data</p>

                        <div x-show="sidebarMini" class="flex justify-center mb-3">
                            <div class="w-5 border-b-2 border-primary-200 rounded-full"></div>
                        </div>

                        <a title="Kelola Dosen" :class="sidebarMini ? 'justify-center' : 'justify-start px-4'"
                            class="flex items-center py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dosen*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}"
                            href="{{ route('admin.dosen') }}">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span x-show="!sidebarMini" class="ml-3 font-medium text-sm whitespace-nowrap">Kelola
                                Dosen</span>
                        </a>

                        <a title="Kelola Mahasiswa" :class="sidebarMini ? 'justify-center' : 'justify-start px-4'"
                            class="flex items-center mt-1 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.mahasiswa*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-primary-50 hover:text-primary-700' }}"
                            href="{{ route('admin.mahasiswa') }}">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                </path>
                            </svg>
                            <span x-show="!sidebarMini" class="ml-3 font-medium text-sm whitespace-nowrap">Kelola
                                Mahasiswa</span>
                        </a>
                    </div>
                @endif
            </nav>
        </div>

        <div class="flex flex-col flex-1 overflow-hidden bg-[#f5faf6]">

            <header
                class="flex items-center justify-between px-8 py-4 bg-white border-b border-gray-100 border-t-4 border-t-primary-600 shadow-sm z-10">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="text-gray-500 hover:text-primary-600 focus:outline-none lg:hidden transition-colors">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6H20M4 12H20M4 18H11"></path>
                        </svg>
                    </button>

                    <button @click="sidebarMini = !sidebarMini"
                        class="hidden lg:block text-gray-500 hover:text-primary-600 focus:outline-none transition-colors bg-gray-50 p-2 rounded-lg border border-gray-100 hover:bg-primary-50">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center">
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = ! dropdownOpen"
                            class="flex items-center focus:outline-none transition-transform hover:scale-105">
                            <div class="mr-3 text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-primary-600 mt-1 uppercase font-bold tracking-widest">
                                    {{ Auth::user()->role }}</p>
                            </div>
                            <img class="object-cover w-10 h-10 rounded-full border-2 border-primary-100 shadow-sm"
                                src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=145161&color=fff&bold=true"
                                alt="Avatar">
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition
                            class="absolute right-0 z-20 w-48 mt-3 overflow-hidden bg-white rounded-xl shadow-xl border border-gray-100"
                            style="display: none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-3 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 font-medium transition">Profil
                                Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 font-medium transition border-t border-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto">
                @if (isset($header))
                    <div class="px-8 py-6 bg-white border-b border-gray-100 flex items-center">
                        <h2 class="font-extrabold text-2xl text-gray-800 tracking-tight">
                            {{ $header }}
                        </h2>
                    </div>
                @endif

                <div class="p-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('scripts')
</body>

</html>
