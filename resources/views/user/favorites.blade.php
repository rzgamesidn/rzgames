<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZGAMES - My Wishlist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #030712; color: white; }
        .product-card:hover { transform: translateY(-10px); border-color: #3b82f6; box-shadow: 0 20px 40px -20px rgba(59, 130, 246, 0.3); }
    </style>
</head>
<body class="antialiased">

    {{-- LOGIC DARURAT: Mengambil data produk --}}
    @php
        if(auth()->check()){
            // Ambil produk dari tabel favorites untuk user login
            $displayProducts = \App\Models\Product::whereIn('id', 
                \App\Models\Favorite::where('user_id', auth()->id())->pluck('product_id')
            )->get();
        } else {
            // Ambil produk dari session untuk guest
            $sessionIds = session()->get('favorites', []);
            $displayProducts = \App\Models\Product::whereIn('id', $sessionIds)->get();
        }
    @endphp

    {{-- NAVBAR (Simple Version) --}}
    <nav class="bg-gray-900/80 backdrop-blur-md border-b border-gray-800 sticky top-0 z-50 w-full">
        <div class="w-full px-4 md:px-6 h-20 flex justify-between items-center">
            <a href="{{ route('user.home') }}" class="flex items-center gap-3">
                <h1 class="text-xl font-black italic text-white">RZGAMES</h1>
            </a>
            <div class="flex items-center gap-6">
                <a href="{{ route('favorite.index') }}" class="text-blue-500 relative">
                    <i class="fa-solid fa-heart text-sm"></i>
                    <span class="absolute -top-2 -right-2 bg-red-600 text-[8px] px-1.5 py-0.5 rounded-full text-white font-bold">{{ count($displayProducts) }}</span>
                </a>
                @guest
                    <a href="{{ route('login') }}" class="text-[10px] font-bold text-gray-400 uppercase">Login</a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto px-6 py-20 min-h-screen">
        <div class="mb-16">
            <h3 class="text-4xl font-black uppercase italic border-l-8 border-blue-600 pl-6 tracking-widest text-white">
                My Wishlist <span class="text-blue-600">❤️</span>
            </h3>
        </div>

        @if($displayProducts->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 text-center bg-gray-900/20 rounded-[3rem] border border-dashed border-gray-800">
                <div class="text-7xl mb-6 grayscale opacity-30">🎮</div>
                <h4 class="text-xl font-black uppercase text-white mb-2">Wishlist Masih Kosong!</h4>
                <a href="{{ route('user.home') }}" class="px-10 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase transition-all shadow-lg shadow-blue-600/20">Cari Game Sekarang</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                @foreach($displayProducts as $p)
                <div class="product-card group relative bg-gray-900 rounded-[2.5rem] overflow-hidden border border-gray-800 transition-all duration-500 shadow-2xl">
                    <div class="relative aspect-[3/4] overflow-hidden">
                        <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover">
                        
                        <form action="{{ route('favorite.add', $p->id) }}" method="POST" class="absolute top-4 right-4 z-20">
                            @csrf
                            <button type="submit" class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center shadow-xl">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                        </form>

                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center p-6 backdrop-blur-[2px]">
                            <a href="{{ route('user.detail', $p->id) }}" class="w-full py-3 bg-white text-black text-center rounded-xl text-[10px] font-black uppercase">Lihat Detail</a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h4 class="text-white font-black text-sm truncate uppercase mb-4">{{ $p->title }}</h4>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                            <span class="text-blue-500 font-black text-xs">Rp {{ number_format($p->price) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>