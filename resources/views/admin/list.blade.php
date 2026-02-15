<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Game - RZGAMES Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
    </style>
</head>
<body class="bg-[#0b0f1a] text-white antialiased">

    {{-- Background Glow --}}
    <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full"></div>
    </div>

    <div class="relative z-10 min-h-screen">
        {{-- Custom Navbar --}}
        <nav class="border-b border-white/5 bg-black/20 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-[1400px] mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/30 font-black text-white italic">RZ</div>
                    <h1 class="font-black text-xl tracking-tighter uppercase italic">Inventory <span class="text-blue-500">Center</span></h1>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-white transition py-2 px-4">Dashboard</a>
                    <a href="{{ route('admin.add') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-black text-xs uppercase tracking-widest transition shadow-lg shadow-blue-500/20 transform hover:-translate-y-0.5 active:scale-95">
                        + Tambah Game Baru
                    </a>
                </div>
            </div>
        </nav>

        <main class="max-w-[1400px] mx-auto px-6 py-8">
            
            {{-- Header Title Ringkas --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black tracking-tighter italic uppercase italic">
                        Inventory <span class="text-blue-500">Katalog</span>
                    </h2>
                    <p class="text-gray-500 font-bold uppercase tracking-widest text-[9px]">
                        @if(request('search'))
                            Hasil Pencarian: "{{ request('search') }}"
                        @else
                            Total: {{ $products->total() }} Data Terenkripsi
                        @endif
                    </p>
                </div>
                
                {{-- Search Bar yang sudah Berfungsi --}}
                <form action="{{ route('admin.list') }}" method="GET" class="relative group">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Cari Judul Game..." 
                            class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 pr-10 text-xs outline-none focus:border-blue-500/50 transition-all w-64 text-white placeholder:text-gray-600"
                        >
                        @if(request('search'))
                            <a href="{{ route('admin.list') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white transition-colors text-sm">
                                ✕
                            </a>
                        @else
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-600 group-hover:text-blue-500 transition-colors">
                                🔍
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 glass-card border-emerald-500/30 bg-emerald-500/5 text-emerald-400 rounded-xl font-bold text-xs flex items-center gap-3 animate-pulse">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Grid Katalog Versi Compact --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @forelse($products as $p)
                <div class="glass-card rounded-3xl overflow-hidden group hover:border-blue-500/50 transition-all duration-300">
                    
                    {{-- Image Container --}}
                    <div class="relative aspect-square overflow-hidden bg-gray-900">
                        <img src="{{ asset('storage/' . $p->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 opacity-80 group-hover:opacity-100">
                        
                        {{-- Price Tag Mini --}}
                        <div class="absolute bottom-2 left-2">
                            <span class="bg-blue-600/90 backdrop-blur-md text-white text-[9px] font-black px-2 py-1 rounded-lg italic shadow-lg">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Trending Badge Mini --}}
                        @if($p->is_trending)
                        <div class="absolute top-2 right-2 bg-orange-500 text-white text-[8px] font-black px-2 py-0.5 rounded-md uppercase tracking-tighter shadow-lg">
                            HOT
                        </div>
                        @endif
                    </div>
                    
                    {{-- Details Ringkas --}}
                    <div class="p-3">
                        <h3 class="font-bold text-[11px] text-white truncate uppercase italic tracking-tighter mb-3 group-hover:text-blue-400 transition-colors">
                            {{ $p->title }}
                        </h3>
                        
                        <div class="flex gap-2">
                            {{-- Edit Button --}}
                            <a href="{{ route('admin.edit', $p->id) }}" class="flex-1 bg-white/5 hover:bg-blue-600 text-white py-1.5 rounded-lg text-[9px] font-black uppercase transition flex items-center justify-center border border-white/5 shadow-inner">
                                🛠️
                            </a>
                            
                            {{-- Delete Button --}}
                            <form action="{{ route('admin.delete', $p->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus data ini Bang?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white py-1.5 rounded-lg text-[9px] font-black transition border border-red-500/20 shadow-inner">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                {{-- Empty State masuk ke dalam Grid --}}
                <div class="col-span-full flex flex-col items-center justify-center py-20 glass-card rounded-[2rem] border-dashed border-2 border-white/5">
                    <span class="text-4xl mb-4 opacity-20">🎮</span>
                    <h3 class="text-sm font-black text-gray-600 uppercase italic">Data Tidak Ditemukan</h3>
                    <p class="text-gray-700 text-[10px] mt-1 uppercase font-bold tracking-widest text-center">Keyword "{{ request('search') }}" tidak cocok dengan protokol manapun.</p>
                    <a href="{{ route('admin.list') }}" class="mt-4 text-blue-500 text-[10px] font-black uppercase underline">Reset Database</a>
                </div>
                @endforelse
            </div>

            {{-- Pagination dengan Appends agar search tidak hilang --}}
            <div class="mt-8">
                {{ $products->appends(['search' => request('search')])->links() }}
            </div>
        </main>

        <footer class="py-12 text-center text-gray-600 text-[10px] font-black uppercase tracking-[0.4em]">
            RZGAMES Admin Console — High Performance Management
        </footer>
    </div>

</body>
</html>