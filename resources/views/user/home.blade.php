<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZGAMES - Store</title>

    <style>
        html { scroll-behavior: smooth; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* --- TEMPEL DI SINI BANG --- */
        #trending-scroll {
            cursor: grab;
            user-select: none; /* Biar pas digeser teksnya nggak ke-highlight biru */
        }
        #trending-scroll:active {
            cursor: grabbing;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-950 text-white antialiased">

{{-- NAVBAR --}}
<nav class="bg-gray-900/80 backdrop-blur-md border-b border-gray-800 sticky top-0 z-50 w-full">
    <div class="w-full px-4 md:px-10 h-20 flex justify-between items-center">
        
        {{-- SISI KIRI: LOGO --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('user.home') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 overflow-hidden rounded-xl shadow-lg shadow-blue-500/20 group-hover:scale-110 transition duration-300">
                    <img src="{{ asset('Screenshot 2026-02-09 224928.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-black italic tracking-tighter text-white">RZGAMES</h1>
            </a>
        </div>
        
        {{-- SISI KANAN: MENU --}}
<div class="flex items-center gap-2 md:gap-8">
    {{-- Kita hapus 'hidden lg:flex' biar dia muncul di semua layar --}}
    {{-- Kita pake 'flex' aja dan 'gap-3' biar nggak terlalu rapet di HP --}}
    <div class="flex gap-3 md:gap-8 text-xs font-bold uppercase tracking-widest text-gray-400 items-center">
        
        {{-- Search Bar (Kita sembunyiin di HP biar gak sempit, atau biarin kecil) --}}
        <form action="/search" method="GET" class="relative group hidden sm:block"> 
            <input type="text" name="query" placeholder="Search..." 
                   class="bg-gray-800 border-none rounded-full px-4 py-1.5 text-[10px] w-20 focus:w-32 transition-all outline-none">
        </form>

        {{-- Keranjang --}}
        <a href="{{ route('cart.index') }}" class="hover:text-blue-500 transition flex items-center group relative">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-hover:scale-110">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
            <span class="hidden md:block">Keranjang</span> {{-- Teksnya ilangin di HP, icon aja --}}
            {{-- Badge jumlah barang tetep muncul --}}
            @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 px-1.5 py-0.5 bg-blue-600 text-white rounded-full text-[8px] font-black">{{ $cartCount }}</span>
            @endif
        </a>

        {{-- Favorit --}}
        <a href="{{ route('favorite.index') }}" class="flex items-center gap-2 hover:text-red-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
            <span class="hidden md:block">Favorit</span>
        </a>

        {{-- History --}}
        @auth
            <a href="{{ route('order.history') }}" class="flex items-center gap-2 hover:text-blue-500 transition">
                <span class="text-sm">📜</span> 
                <span class="hidden md:block">History</span>
            </a>
        @endauth
    </div>
                @endauth
            </div>
            
            {{-- Auth Buttons --}}
            <div class="flex items-center gap-4 ml-4">
                @auth
                    <div class="flex items-center gap-4">
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl text-[10px] font-black transition shadow-lg shadow-red-500/30">
                                ADMIN PANEL
                            </a>
                        @else
                            <span class="hidden sm:block text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Hi, {{ Auth::user()->name }}
                            </span>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold text-red-500 hover:text-red-400 uppercase tracking-widest">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl text-sm font-black transition shadow-lg shadow-blue-500/30">JOIN NOW</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@if(!request('query'))
<div class="max-w-[1800px] mx-auto px-6 mt-8 mb-12">
    <div class="w-full h-[200px] md:h-[400px] relative overflow-hidden rounded-[2.5rem] shadow-2xl border border-gray-800 group">
        <img src="https://4kwallpapers.com/images/walls/thumbs_3t/8247.jpg" class="w-full h-full object-cover group-hover:scale-105 transition duration-1000" alt="Banner RZGAMES">
        <div class="absolute inset-0 bg-black/40"></div>
    </div>
</div>
@endif

<main class="max-w-[1400px] mx-auto px-6 pb-24 mt-8">
    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 px-6 py-4 rounded-2xl mb-8 flex justify-between items-center">
            <span class="text-sm font-bold">✅ {{ session('success') }}</span>
        </div>
    @endif

  {{-- SECTION TRENDING (FIX SLIDER & TINGGI RATA) --}}
<section class="mb-12">
    <div class="flex justify-between items-center mb-6 px-4 md:px-0">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-7 bg-blue-600 rounded-full"></div>
            <h3 class="text-lg font-black text-white uppercase italic tracking-tight">Most Purchased</h3>
        </div>
        {{-- Navigasi --}}
        <div class="flex gap-2">
            <button onclick="scrollSlider('left')" class="w-9 h-9 bg-gray-800 rounded-xl hover:bg-blue-600 transition flex items-center justify-center border border-gray-700">
                <i class="fa-solid fa-chevron-left text-xs text-white"></i>
            </button>
            <button onclick="scrollSlider('right')" class="w-9 h-9 bg-gray-900 rounded-xl hover:bg-blue-600 transition flex items-center justify-center border border-gray-700">
                <i class="fa-solid fa-chevron-right text-xs text-white"></i>
            </button>
        </div>
    </div>

    {{-- Container Slider --}}
<div id="trending-scroll" class="flex flex-nowrap gap-4 overflow-x-auto no-scrollbar scroll-smooth pb-6 px-4 md:px-0">
    @forelse($trendingProducts as $tp)
        {{-- Lebar dipaksa sama (w-[200px] atau w-[240px]) --}}
        <div class="w-[180px] md:w-[230px] flex-shrink-0 group">
            <div class="bg-gray-900/40 border border-gray-800 rounded-[2rem] p-3 hover:border-blue-500/50 transition-all duration-300 h-full flex flex-col">
                
                {{-- Image Box - Perbaiki Aspect Ratio --}}
                <div class="relative aspect-[3/4] w-full rounded-[1.5rem] overflow-hidden mb-3 shadow-lg bg-gray-800">
                    <img src="{{ asset('storage/' . $tp->image) }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                         <a href="{{ route('user.detail', $tp->id) }}" class="text-[10px] bg-white text-black px-4 py-2 rounded-xl font-black uppercase shadow-xl">Detail</a>
                    </div>
                </div>

                {{-- Info Produk --}}
                <div class="flex flex-col flex-grow justify-between">
                    <h4 class="text-white font-bold text-[12px] md:text-[13px] leading-tight line-clamp-2 mb-2 h-8 uppercase">
                        {{ $tp->title }}
                    </h4>
                    
                    <div class="flex justify-between items-center bg-black/20 p-2 rounded-xl border border-white/5">
                        <div class="flex flex-col">
                            @if($tp->original_price && $tp->original_price > $tp->price)
                                <span class="text-[8px] text-gray-500 line-through">Rp{{ number_format($tp->original_price, 0, ',', '.') }}</span>
                            @endif
                            <span class="text-blue-400 font-black text-[12px]">Rp{{ number_format($tp->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <form action="{{ route('cart.add', $tp->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-500 active:scale-90 transition">
                                <i class="fa-solid fa-plus text-white text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-gray-500 text-xs italic px-4">Belum ada item pilihan, Bang.</p>
    @endforelse
</div>
</section>

    

{{-- KATALOG PRODUK --}}
<div id="katalog" class="mb-10">
    <div class="flex items-center gap-4">
        <div class="w-2 h-8 bg-blue-600 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.6)]"></div>
        <h3 class="text-2xl font-black uppercase italic tracking-wider">Katalog Produk</h3>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 md:gap-8">
    @forelse($products as $p)
        <div class="bg-gray-900/50 rounded-[2rem] overflow-hidden border border-gray-800 hover:border-blue-500 transition-all duration-300 p-4 group relative flex flex-col h-full">
            
            {{-- Badge Diskon --}}
            @if($p->original_price && $p->original_price > $p->price)
                @php $diskon = round((($p->original_price - $p->price) / $p->original_price) * 100); @endphp
                <div class="absolute top-6 left-6 z-30 bg-red-600 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg">
                    -{{ $diskon }}%
                </div>
            @endif

            {{-- Tombol Favorit --}}
            <form action="{{ route('favorite.add', $p->id) }}" method="POST" class="absolute top-6 right-6 z-30">
                @csrf
                <button type="submit" class="w-9 h-9 bg-black/40 backdrop-blur-md rounded-xl flex items-center justify-center text-white hover:text-red-500 transition-all border border-white/5 shadow-xl active:scale-90">
                    <i class="fa-solid fa-heart text-xs"></i>
                </button>
            </form>

            {{-- Image & Info --}}
            <a href="{{ route('user.detail', $p->id) }}" class="flex-1 z-10 block group/link">
                <div class="aspect-[3/4] overflow-hidden rounded-[1.5rem] mb-4 relative shadow-2xl">
                    <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                
                <div class="px-1">
                    <h4 class="text-white font-bold text-sm truncate group-hover/link:text-blue-400 transition-colors uppercase tracking-tight">{{ $p->title }}</h4>
                    
                    <div class="flex flex-col mt-2">
                        @if($p->original_price && $p->original_price > $p->price)
                            <span class="text-[10px] text-gray-500 line-through decoration-red-500 font-bold italic">
                                Rp {{ number_format($p->original_price, 0, ',', '.') }}
                            </span>
                        @endif
                        <p class="text-blue-500 font-black text-sm">
                            Rp {{ number_format($p->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </a>

            {{-- Tombol Aksi (Teks titik tiga tadi sudah saya ganti dengan tombol beneran) --}}
            <div class="mt-5 relative z-30 flex flex-col gap-2">
                <form action="{{ route('cart.checkout', $p->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 active:scale-95 text-white shadow-lg shadow-blue-900/20">
                        <i class="fa-solid fa-bolt text-xs"></i>
                        Beli Sekarang
                    </button>
                </form>

                <form action="{{ route('cart.add', $p->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-white/5 border border-white/10 hover:bg-gray-800 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 active:scale-95 text-white/80">
                        <i class="fa-solid fa-cart-plus text-xs"></i>
                        + Keranjang
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center bg-gray-900/20 rounded-[3rem] border border-dashed border-gray-800">
            <p class="text-gray-500 italic">Lagi kosong nih stoknya, Bang.</p>
        </div>
    @endforelse
</div>

{{-- WA FLOATING --}}
    <a href="https://wa.me/6285774410978" target="_blank" class="fixed bottom-6 right-6 z-[999] group flex items-center justify-center">
        <span class="absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-20 animate-ping"></span>
        <div class="relative w-14 h-14 bg-green-500 rounded-full flex items-center justify-center shadow-lg border border-green-400 transition-all duration-300 group-hover:scale-110">
            <i class="fa-brands fa-whatsapp text-white text-3xl"></i>
        </div>
    </a>


{{-- FOOTER RZGAMES CLEAN & SOLID VERSION --}}
@if(request()->path() != 'search')
    {{-- Kita tambahkan 'relative' dan 'z-50' biar dia di depan konten produk --}}
    {{-- 'bg-[#020205]' harus sama persis dengan background utama body biar nyambung --}}
    <footer class="relative z-50 bg-[#020205] py-20 mt-20 text-center">
        
        {{-- Divider Minimalis dengan Glow Tipis --}}
        <div class="flex justify-center items-center gap-4 mb-8 opacity-20">
            <span class="h-[1px] w-20 bg-gradient-to-r from-transparent to-blue-500"></span>
            <div class="w-1.5 h-1.5 rounded-full bg-blue-500 shadow-[0_0_10px_#3b82f6]"></div>
            <span class="h-[1px] w-20 bg-gradient-to-l from-transparent to-blue-500"></span>
        </div>
        
        <p class="text-white/20 text-[10px] font-black uppercase tracking-[0.6em] hover:text-blue-400 transition-all duration-700 cursor-default">
            &copy; 2026 RZGAMES STORE — RZCOMPANY
        </p>
        
    </footer>
@endif

<script>
        // Fungsi Tombol Panah
        function scrollSlider(direction) {
            const slider = document.getElementById('trending-scroll');
            const scrollAmount = 300;
            if (direction === 'left') {
                slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

        // FITUR TAMBAHAN: Geser Pakai Mouse (Drag to Scroll)
        const slider = document.getElementById('trending-scroll');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
        });
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // Kecepatan geser
            slider.scrollLeft = scrollLeft - walk;
        });
    </script>

</body>
</html>