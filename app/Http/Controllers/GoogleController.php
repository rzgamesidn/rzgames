<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle() {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        $user = Socialite::driver('google')->user();
        
        // Cari user di database berdasarkan email atau google_id
        $findUser = User::where('google_id', $user->id)
                        ->orWhere('email', $user->email)
                        ->first();

        if($findUser){
            // Kalau ketemu, langsung loginin Bang
            $findUser->update(['google_id' => $user->id]);
            Auth::login($findUser);
        } else {
            // Kalau belum ada, buat akun baru otomatis
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'google_id'=> $user->id,
                'password' => encrypt('password_acak_bang') // User gak butuh password ini karena login via google
            ]);
            Auth::login($newUser);
        }

        return redirect()->route('user.home');
    }
}