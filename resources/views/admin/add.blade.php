<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Game - RZGAMES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] antialiased">

    <div class="py-12 min-h-screen relative overflow-hidden flex items-center justify-center">
        {{-- Background Dekorasi Glow --}}
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-96 h-96 bg-blue-500/20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 translate-y-12 -translate-x-12 w-96 h-96 bg-indigo-500/20 blur-[120px] rounded-full"></div>

        <div class="w-full max-w-4xl mx-auto px-4 relative z-10">
            
            {{-- Header --}}
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-4xl font-black text-white tracking-tighter italic uppercase">
                        Tambah <span class="text-blue-500 underline decoration-blue-500/30">Game Baru</span> 🚀
                    </h2>
                    <p class="text-gray-400 text-sm mt-2 font-medium">Panel Admin RZGAMES — Kelola Inventory dengan Style.</p>
                </div>
                <a href="{{ route('admin.list') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-white transition uppercase tracking-widest border-b border-white/10 hover:border-blue-500 pb-1">
                    ← Kembali ke List
                </a>
            </div>

            {{-- Card Utama --}}
            <div class="bg-gray-900/40 backdrop-blur-2xl border border-white/10 shadow-[0_32px_64px_-15px_rgba(0,0,0,0.5)] rounded-[3rem] overflow-hidden">
                <div class="p-8 md:p-12">
                    
                    {{-- Alert Error --}}
                    @if ($errors->any())
                        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
                            <ul class="list-disc list-inside text-red-400 text-xs font-bold uppercase tracking-tight">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                        @csrf

                        {{-- Upload Section --}}
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Visual Game (1024x1024)</label>
                            <label class="group relative flex flex-col items-center justify-center w-full h-80 border-2 border-dashed border-white/5 rounded-[2.5rem] cursor-pointer bg-white/[0.02] hover:bg-blue-500/[0.03] hover:border-blue-500/30 transition-all duration-500">
                                <div class="flex flex-col items-center justify-center py-10">
                                    <div class="w-20 h-20 bg-blue-600/10 rounded-3xl flex items-center justify-center mb-6 text-3xl group-hover:scale-110 transition-transform duration-500 shadow-inner">📸</div>
                                    <p class="text-base text-gray-200 font-extrabold">Drop image or click to upload</p>
                                    <p class="text-[10px] text-gray-500 mt-2 font-bold uppercase tracking-widest">PNG, JPG, WEBP — Max 10MB</p>
                                </div>
                                <input type="file" name="image" class="hidden" accept="image/*" required />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                            {{-- Judul --}}
                            <div class="md:col-span-2 space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Nama Judul Game</label>
                                <input type="text" name="title" placeholder="e.g. Cyberpunk 2077 Phantom Liberty" 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white placeholder-gray-600 focus:ring-2 focus:ring-blue-500/50 focus:bg-white/[0.05] outline-none transition-all font-bold text-lg">
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2 space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Sinopsis & Detail</label>
                                <textarea name="description" rows="5" placeholder="Tulis deskripsi yang bikin orang pengen beli..." 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white placeholder-gray-600 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-medium leading-relaxed"></textarea>
                            </div>

                            {{-- Link Drive --}}
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Google Drive Link</label>
                                <input type="url" name="drive_link" placeholder="https://drive.google.com/..." 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white placeholder-gray-600 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-mono text-xs italic">
                            </div>

                            {{-- Link Cek Game --}}
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Official Store Link</label>
                                <input type="url" name="game_link" placeholder="https://store.steampowered.com/..." 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white placeholder-gray-600 focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-mono text-xs italic">
                            </div>

                            {{-- Harga --}}
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Harga Utama (IDR)</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-500 font-black">Rp</span>
                                    <input type="number" name="price" placeholder="150000" 
                                        class="w-full bg-white/[0.03] border border-white/5 rounded-2xl pl-16 pr-6 py-5 text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-black text-2xl tracking-tighter">
                                </div>
                            </div>                        

                            {{-- Harga Sale --}}
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-emerald-400 uppercase tracking-[0.4em]">Harga Sale (Optional)</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-500 font-black">Rp</span>
                                    <input type="number" name="original_price" placeholder="99000" 
                                        class="w-full bg-white/[0.03] border border-white/5 rounded-2xl pl-16 pr-6 py-5 text-emerald-400 focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all font-black text-2xl tracking-tighter placeholder-emerald-900/50">
                                </div>
                            </div>
                        </div>

                        {{-- Input Priority untuk Tambah Game --}}
                            <div class="mb-4">
                                <label class="block text-[10px] font-black text-blue-400 uppercase tracking-[0.2em] mb-2 italic">
                                    Priority Level (Urutan Tampilan)
                                </label>
                                <div class="relative group">
                                    <input 
                                        type="number" 
                                        name="priority" 
                                        value="0" 
                                        placeholder="Contoh: 100" 
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm outline-none focus:border-blue-500/50 transition-all text-white font-bold"
                                    >
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] text-gray-500 font-bold italic uppercase group-focus-within:text-blue-500 transition-colors">
                                        Higher = Top
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-600 mt-2 font-bold italic uppercase tracking-wider">
                                    *Isi angka tinggi (misal: 100) untuk memaksa game lama naik ke posisi atas.
                                </p>
                            </div>

                        {{-- Trending Feature --}}
                        <label class="group flex items-center gap-5 p-6 bg-blue-600/[0.03] border border-blue-500/10 rounded-[2rem] cursor-pointer hover:bg-blue-600/[0.06] transition-all">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="is_trending" value="1" class="peer sr-only">
                                <div class="w-14 h-8 bg-gray-800 rounded-full peer peer-checked:bg-blue-600 transition-all after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                            <div>
                                <span class="block font-black text-white text-sm uppercase italic tracking-widest group-hover:text-blue-400 transition-colors">🔥 Set as Trending Game</span>
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Muncul di Spotlight Utama Website</span>
                            </div>
                        </label>

                        {{-- Submit --}}
                        <div class="pt-4">
                            <button type="submit" class="group relative w-full overflow-hidden rounded-[2rem]">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-700 transition-all group-hover:scale-105"></div>
                                <div class="relative py-6 flex items-center justify-center gap-3">
                                    <span class="text-white font-black uppercase italic tracking-[0.3em] text-sm">Publish To Market</span>
                                    <span class="text-xl group-hover:translate-x-2 transition-transform">🚀</span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer info --}}
            <p class="text-center mt-10 text-gray-600 text-[10px] font-bold uppercase tracking-[0.2em]">RZGAMES Management System v2.0</p>
        </div>
    </div>
</body>
</html>