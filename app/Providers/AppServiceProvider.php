<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite; // Cukup satu aja bang, jangan serakah hehe
use App\Models\Cart;     // IMPORT INI

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Biar variabel $favorites bisa dibaca di semua halaman (Navbar aman)
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('favorites', Favorite::where('user_id', Auth::id())->get());
            } else {
                $view->with('favorites', collect());
            }
        });
    }
}