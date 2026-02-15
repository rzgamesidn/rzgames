<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RZGAMES - Authorize') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body class="antialiased selection:bg-blue-500/30">
    <div class="min-h-screen flex items-center justify-center bg-[#020205] relative overflow-hidden font-sans">
        
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-purple-600/10 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>

        <div class="relative w-full sm:max-w-[450px] px-8 py-12 bg-white/[0.02] backdrop-blur-2xl border border-white/5 rounded-[3rem] shadow-[0_20px_80px_rgba(0,0,0,0.8)] mx-4 transition-all duration-500 hover:border-white/10">
            
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-700 rounded-3xl shadow-[0_0_40px_rgba(59,130,246,0.3)] mb-6 float-animation transition-transform hover:scale-110">
                    <span class="text-white font-black italic text-3xl tracking-tighter">RZ</span>
                </div>
                
                <h2 class="text-4xl font-black italic text-white tracking-tighter uppercase leading-tight">System Login</h2>
                <div class="flex justify-center items-center gap-2 mt-3">
                    <span class="h-[1px] w-8 bg-blue-500/50"></span>
                    <p class="text-blue-400/80 text-[10px] font-black uppercase tracking-[0.5em]">Command Center</p>
                    <span class="h-[1px] w-8 bg-blue-500/50"></span>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-7">
                @csrf

                <div class="group relative">
                    <label class="block text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-2 ml-4 group-focus-within:text-blue-400 transition-all">Identity / Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="commander@rzgames.com"
                            class="w-full bg-white/[0.03] border border-white/5 rounded-2xl py-4 px-6 text-white placeholder-white/10 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:bg-white/[0.07] focus:border-blue-500/50 transition-all duration-300">
                        <div class="absolute inset-0 rounded-2xl bg-blue-500/5 opacity-0 group-focus-within:opacity-100 pointer-events-none transition-opacity"></div>
                    </div>
                    @if($errors->has('email'))
                        <p class="mt-2 text-[10px] font-bold text-red-500/80 uppercase tracking-widest ml-4 italic">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="group relative">
                    <div class="flex justify-between items-center mb-2 ml-4">
                        <label class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] group-focus-within:text-blue-400 transition-all">Security Code</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[9px] font-bold text-blue-400/50 hover:text-blue-400 uppercase tracking-widest transition-all">Reset?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full bg-white/[0.03] border border-white/5 rounded-2xl py-4 px-6 text-white placeholder-white/10 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:bg-white/[0.07] focus:border-blue-500/50 transition-all duration-300">
                        <div class="absolute inset-0 rounded-2xl bg-purple-500/5 opacity-0 group-focus-within:opacity-100 pointer-events-none transition-opacity"></div>
                    </div>
                    @if($errors->has('password'))
                        <p class="mt-2 text-[10px] font-bold text-red-500/80 uppercase tracking-widest ml-4 italic">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center ml-4 cursor-pointer group w-fit">
                    <div class="relative flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-white/10 bg-white/5 text-blue-600 focus:ring-offset-0 focus:ring-blue-500/50 transition-all">
                    </div>
                    <label for="remember_me" class="ml-3 text-[10px] font-bold text-white/30 group-hover:text-white/60 uppercase tracking-widest transition-colors cursor-pointer">Stay Authenticated</label>
                </div>
                

                <button type="submit" class="w-full relative group overflow-hidden bg-gradient-to-r from-blue-600 to-blue-500 rounded-2xl py-4 shadow-[0_10px_20px_rgba(59,130,246,0.2)] hover:shadow-[0_15px_30px_rgba(59,130,246,0.4)] transition-all duration-500 active:scale-[0.98]">
                    <div class="absolute inset-0 w-[200%] h-full bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-[-30deg] left-[-200%] group-hover:left-[200%] transition-all duration-1000"></div>
                    <span class="relative text-white font-black uppercase text-xs tracking-[0.4em]">Initialize Connection</span>
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

                <div class="pt-8 text-center border-t border-white/5">
                    <p class="text-[10px] font-bold text-white/10 uppercase tracking-[0.2em]">
                        No credentials? <a href="{{ route('register') }}" class="text-blue-500/60 hover:text-blue-400 transition-all font-black">Request Access</a>
                    </p>

                    
                </div>
            </form>
        </div>
    </div>
</body>
</html>