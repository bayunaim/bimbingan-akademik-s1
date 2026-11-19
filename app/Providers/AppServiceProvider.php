<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // 1. Tambahkan ini bro

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Admin pusat ATAU kaprodi: boleh masuk menu kelola dosen/mahasiswa
        Gate::define('kelola', function ($user) {
            return in_array($user->role, ['admin', 'kaprodi']);
        });

        Gate::define('dosen', function ($user) {
            return $user->role === 'dosen';
        });

        Gate::define('mahasiswa', function ($user) {
            return $user->role === 'mahasiswa';
        });
    }
}
