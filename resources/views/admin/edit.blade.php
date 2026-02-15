<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game - {{ $product->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#0f172a] antialiased">

    <div class="py-12 min-h-screen relative overflow-hidden">
        {{-- Background Glow Dekorasi --}}
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-[500px] h-[500px] bg-blue-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 translate-y-12 -translate-x-12 w-[500px] h-[500px] bg-indigo-500/10 blur-[120px] rounded-full"></div>

        <div class="max-w-5xl mx-auto px-4 relative z-10">
            
            {{-- Header --}}
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-blue-500 font-black text-[10px] uppercase tracking-[0.5em] mb-2 block">Management Mode</span>
                    <h2 class="text-4xl font-black text-white tracking-tighter italic uppercase">
                        Edit <span class="text-blue-500 underline decoration-blue-500/30">Game Data</span> 🛠️
                    </h2>
                    <p class="text-gray-400 text-sm mt-2 font-medium">Mengubah: <span class="text-white font-bold">{{ $product->title }}</span></p>
                </div>
                <a href="{{ route('admin.list') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-white transition uppercase tracking-widest border-b border-white/10 hover:border-blue-500 pb-1">
                    ← Kembali ke List
                </a>
            </div>

            {{-- Card Utama --}}
            <div class="bg-gray-900/40 backdrop-blur-2xl border border-white/10 shadow-2xl rounded-[3rem] overflow-hidden">
                <div class="p-8 md:p-12">
                    
                    <form action="{{ route('admin.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
                        @csrf
                        @method('PUT')

                        {{-- Section 1: Media --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                            <div class="space-y-4">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Preview Cover</label>
                                <div class="relative group">
                                    <div class="absolute inset-0 bg-blue-500/20 blur-2xl rounded-[2.5rem] group-hover:bg-blue-500/40 transition-all"></div>
                                    <img id="img-preview" 
                                         src="{{ asset('storage/' . $product->image) }}" 
                                         class="relative w-full aspect-square object-cover rounded-[2.5rem] border-2 border-white/10 shadow-2xl group-hover:border-blue-500/50 transition-all duration-500">
                                    <div class="absolute inset-4 border border-white/10 rounded-[2rem] pointer-events-none"></div>
                                </div>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="space-y-4">
                                    <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Upload New Cover</label>
                                    <div class="relative">
                                        <input type="file" name="image" id="image-input" 
                                               class="hidden"
                                               accept="image/*" onchange="previewImage()">
                                        <label for="image-input" class="flex items-center justify-between w-full bg-white/[0.03] border border-white/10 rounded-2xl px-6 py-5 cursor-pointer hover:bg-white/[0.06] transition-all group">
                                            <span class="text-gray-400 font-bold group-hover:text-white transition-colors">Pilih file gambar baru...</span>
                                            <span class="bg-blue-600 text-white text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest shadow-lg shadow-blue-500/40">Browse</span>
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-gray-500 font-bold italic tracking-wider uppercase">*Kosongkan jika tidak ingin mengganti visual.</p>
                                    @error('image') <span class="text-red-500 text-[10px] font-bold mt-2 uppercase italic">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-4">
                                    <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Judul Game</label>
                                    <input type="text" name="title" value="{{ old('title', $product->title) }}" 
                                        class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-black text-xl italic uppercase tracking-tighter">
                                </div>
                            </div>
                        </div>

                        <hr class="border-white/5">

                        {{-- Section 2: Info & Links --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="md:col-span-2 space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Sinopsis & Detail Produk</label>
                                <textarea name="description" rows="5" 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-[2rem] px-6 py-6 text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-medium leading-relaxed shadow-inner">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Google Drive Link (Download)</label>
                                <input type="url" name="drive_link" value="{{ old('drive_link', $product->drive_link) }}" 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white font-mono text-xs italic focus:ring-2 focus:ring-blue-500/50 outline-none transition-all shadow-inner">
                            </div>

                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Official Store Link (Opsional)</label>
                                <input type="url" name="game_link" value="{{ old('game_link', $product->game_link) }}" 
                                    class="w-full bg-white/[0.03] border border-white/5 rounded-2xl px-6 py-5 text-white font-mono text-xs italic focus:ring-2 focus:ring-blue-500/50 outline-none transition-all shadow-inner">
                            </div>

                            {{-- Harga --}}
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-blue-400 uppercase tracking-[0.4em]">Harga Jual (IDR)</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-blue-500 font-black">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                                        class="w-full bg-white/[0.03] border border-white/5 rounded-2xl pl-16 pr-6 py-5 text-white focus:ring-2 focus:ring-blue-500/50 outline-none transition-all font-black text-2xl tracking-tighter">
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-emerald-400 uppercase tracking-[0.4em]">Harga Coret (IDR)</label>
                                <div class="relative group">
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-500 font-black">Rp</span>
                                    <input type="number" name="original_price" value="{{ old('original_price', $product->original_price) }}" 
                                        class="w-full bg-white/[0.03] border border-white/5 rounded-2xl pl-16 pr-6 py-5 text-emerald-400/50 focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all font-black text-2xl tracking-tighter">
                                </div>
                            </div>
                        </div>

                        {{-- Input Priority untuk Edit Game --}}
                            <div class="mb-4">
                                <label class="block text-[10px] font-black text-yellow-500 uppercase tracking-[0.2em] mb-2 italic">
                                    Priority Override
                                </label>
                                <div class="relative group">
                                    <input 
                                        type="number" 
                                        name="priority" 
                                        value="{{ $product->priority }}" 
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm outline-none focus:border-yellow-500/50 transition-all text-white font-bold"
                                    >
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[9px] text-gray-500 font-bold italic uppercase group-focus-within:text-yellow-500 transition-colors">
                                        Current: {{ $product->priority }}
                                    </div>
                                </div>
                                <p class="text-[9px] text-gray-600 mt-2 font-bold italic uppercase tracking-wider">
                                    *Ubah angka ini jadi lebih besar dari produk lain jika ingin memindahkannya ke urutan terdepan.
                                </p>
                            </div>

                        {{-- Trending Toggle --}}
                        <label class="group flex items-center gap-5 p-6 bg-blue-600/[0.03] border border-blue-500/10 rounded-[2.5rem] cursor-pointer hover:bg-blue-600/[0.06] transition-all">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="is_trending" value="1" class="peer sr-only" {{ $product->is_trending ? 'checked' : '' }}>
                                <div class="w-14 h-8 bg-gray-800 rounded-full peer peer-checked:bg-blue-600 transition-all after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-6"></div>
                            </div>
                            <div>
                                <span class="block font-black text-white text-sm uppercase italic tracking-widest group-hover:text-blue-400 transition-colors">Set as Trending Game 🔥</span>
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Menampilkan game di Spotlight Dashboard</span>
                            </div>
                        </label>

                        {{-- Action Buttons --}}
                        <div class="pt-6 flex flex-col md:flex-row gap-4">
                            <button type="submit" class="group relative flex-1 overflow-hidden rounded-[2rem]">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-700 transition-all group-hover:scale-110"></div>
                                <div class="relative py-6 flex items-center justify-center gap-3">
                                    <span class="text-white font-black uppercase italic tracking-[0.3em] text-sm">Update Data Game</span>
                                    <span class="text-xl group-hover:rotate-12 transition-transform">💾</span>
                                </div>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            
            <p class="text-center mt-12 text-gray-600 text-[10px] font-black uppercase tracking-[0.5em]">RZGAMES Management Console</p>
        </div>
    </div>

    <script>
        function previewImage() {
            const image = document.querySelector('#image-input');
            const imgPreview = document.querySelector('#img-preview');
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);
            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
            }
        }
    </script>
</body>
</html>