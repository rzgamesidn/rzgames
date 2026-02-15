<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RZGAMES - Initialize Account') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Tambahin ini buat cadangan kalo Vite lo lagi error --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center bg-[#020205] relative overflow-hidden font-sans">
        
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-purple-600/20 rounded-full blur-[120px]"></div>

        <div class="relative w-full sm:max-w-[480px] px-8 py-10 bg-white/[0.03] backdrop-blur-xl border border-white/10 rounded-[2.5rem] shadow-2xl my-10 mx-4">
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-2xl mb-4 shadow-[0_0_20px_rgba(59,130,246,0.4)]">
                    <span class="text-white font-black italic text-xl">RZ</span>
                </div>
                <h2 class="text-3xl font-black italic text-white tracking-tighter uppercase">Initialize Account</h2>
                <p class="text-blue-400/60 text-[10px] font-bold uppercase tracking-[0.4em] mt-2">Register new operator identity</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="group">
                    <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-blue-400">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full bg-white/[0.05] border border-white/10 rounded-2xl py-4 px-6 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-blue-400">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full bg-white/[0.05] border border-white/10 rounded-2xl py-4 px-6 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="group">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-blue-400">Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            class="w-full bg-white/[0.05] border border-white/10 rounded-2xl py-4 px-6 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-white/40 uppercase tracking-[0.2em] mb-2 ml-1 group-focus-within:text-blue-400">Confirm</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full bg-white/[0.05] border border-white/10 rounded-2xl py-4 px-6 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    </div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />

                <button type="submit" class="w-full relative group overflow-hidden bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl py-4 mt-4 transition-all duration-300 hover:shadow-[0_0_30px_rgba(59,130,246,0.5)]">
                    <span class="relative text-white font-black uppercase text-xs tracking-[0.3em]">REGISTER SYSTEM</span>
                </button>

                {{-- START GOOGLE LOGIN SECTION --}}
                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-white/5"></div>
                    <span class="flex-shrink mx-4 text-[9px] font-black text-white/10 uppercase tracking-[0.3em]">External Auth</span>
                    <div class="flex-grow border-t border-white/5"></div>
                </div>

                <a href="{{ route('google.login') }}" class="w-full group relative flex items-center justify-center gap-3 bg-white/[0.02] border border-white/5 rounded-2xl py-4 transition-all duration-500 hover:bg-white/[0.05] hover:border-blue-500/30 overflow-hidden active:scale-[0.98]">
                    {{-- Efek Cahaya di belakang logo --}}
                    <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    {{-- Logo Google --}}
                    <svg class="w-5 h-5 relative z-10 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>

                    <span class="relative z-10 text-white/50 group-hover:text-blue-400 font-bold uppercase text-[10px] tracking-[0.2em] transition-colors">Authorize via Google</span>
                </a>
                {{-- END GOOGLE LOGIN SECTION --}}

                <div class="mt-8 text-center">
                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-[0.2em]">
                        Already enlisted? <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-400 transition-colors">Return Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>