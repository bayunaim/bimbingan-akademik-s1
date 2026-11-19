<x-guest-layout>

    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h2>
        <p class="text-sm text-gray-500 font-medium mt-1">Masuk ke sistem Bimbingan Akademik</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm font-bold text-emerald-600 bg-emerald-50 p-3 rounded-lg text-center shadow-sm" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-5">
            <label for="email" class="block text-sm font-extrabold text-gray-700 mb-2">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="pl-11 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3.5 transition bg-gray-50 focus:bg-white" placeholder="admin@binaguna.ac.id">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-bold text-rose-500" />
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-extrabold text-gray-700 mb-2">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" class="pl-11 block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm py-3.5 transition bg-gray-50 focus:bg-white" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-bold text-rose-500" />
        </div>

        <div class="flex items-center justify-between mb-8">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 w-4 h-4 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-bold group-hover:text-primary-600 transition">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-primary-600 hover:text-primary-800 transition hover:underline" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-4 px-4 rounded-xl shadow-lg text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition transform hover:-translate-y-1 hover:shadow-primary-600/40">
                Masuk Aplikasi &rarr;
            </button>
        </div>

    </form>
</x-guest-layout>
