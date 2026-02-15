<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL; // WAJIB ADA INI BANG!
use App\Models\Favorite; 
use App\Models\Cart;     

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // JURUS SAKTI: Maksa semua link (CSS/JS) pake HTTPS biar nggak polos lagi
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Biar variabel $favorites & $carts aman di semua halaman
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('favorites', Favorite::where('user_id', Auth::id())->get());
                $view->with('carts', Cart::where('user_id', Auth::id())->get()); // Sekalian keranjangnya Bang
            } else {
                $view->with('favorites', collect());
                $view->with('carts', collect());
            }
        });
    }
}