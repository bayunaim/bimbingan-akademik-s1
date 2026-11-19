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

        Gate::define('dosen', function ($user) {
            return $user->role === 'dosen';
        });

        Gate::define('mahasiswa', function ($user) {
            return $user->role === 'mahasiswa';
        });
    }
}
