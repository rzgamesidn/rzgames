<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->title }} - RZGAMES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 20px; height: 20px;
            top: 50%; left: 50%;
            margin: -10px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-[#050505] text-white antialiased font-['Plus_Jakarta_Sans']">

    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Gambar Produk --}}
        <div class="w-full lg:w-1/2 h-[50vh] lg:h-screen sticky top-0 overflow-hidden">
            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-r from-[#050505] via-transparent to-transparent"></div>
            <a href="{{ route('user.home') }}" class="absolute top-10 left-10 bg-black/50 backdrop-blur-md p-4 rounded-full hover:bg-white hover:text-black transition-all font-bold text-xs uppercase tracking-widest z-50">
                ← Back to Store
            </a>
        </div>

        {{-- Detail Konten --}}
        <div class="w-full lg:w-1/2 p-8 lg:p-24 flex flex-col justify-center">
            <span class="text-blue-500 font-black uppercase tracking-[0.5em] text-xs mb-4 italic">Original Global Key</span>
            <h1 class="text-5xl lg:text-8xl font-black italic tracking-tighter uppercase leading-none mb-8">{{ $product->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-6 mb-12">
                <div class="flex flex-col">
                    @if($product->original_price && $product->original_price > $product->price)
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-xl font-bold italic text-white/30 line-through decoration-red-600">
                                Rp {{ number_format($product->original_price) }}
                            </span>
                            @php
                                $percent = round((($product->original_price - $product->price) / $product->original_price) * 100);
                            @endphp
                            <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded italic">
                                -{{ $percent }}%
                            </span>
                        </div>
                    @endif
                    <span class="text-5xl font-black italic text-blue-500 tracking-tighter">
                        Rp {{ number_format($product->price) }}
                    </span>
                </div>
                <div class="h-12 w-px bg-white/10 hidden md:block"></div>
                <span class="text-xs font-bold text-green-500 uppercase tracking-widest bg-green-500/10 px-4 py-2 rounded-full border border-green-500/20">
                    Instant Delivery ⚡
                </span>
            </div>

            <div class="space-y-8 mb-12">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-white/30 mb-4">Description / System Req</h3>
                    <p class="text-white/60 leading-relaxed text-sm whitespace-pre-line">{{ $product->description }}</p>
                </div>
            </div>

            <div class="mt-8 space-y-4">
    <button type="button" onclick="openModal()" class="w-full bg-blue-600 hover:bg-blue-700 py-6 rounded-2xl font-black italic text-xl uppercase tracking-widest transition-all shadow-2xl shadow-blue-500/20">
        Buy Now
    </button>

    {{-- Tombol Cek Game muncul cuma kalo game_link ada isinya di DB --}}
    @if($product->game_link)
    <a href="{{ $product->game_link }}" target="_blank" 
       class="flex items-center justify-center gap-3 w-full py-4 mt-4 rounded-xl border border-blue-500/30 bg-blue-500/5 hover:bg-blue-500/10 hover:border-blue-500/60 transition-all duration-300 group shadow-lg shadow-blue-900/10">
        <span class="text-[11px] font-black uppercase tracking-[0.3em] text-blue-400 group-hover:text-blue-300 transition-colors">
            🔍 Check Detail Game 
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
    </a>
@endif
</div>
        </div>
    </div>

    {{-- MODAL CHECKOUT --}}
    <div id="checkoutModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
        <div class="bg-[#0f0f0f] border border-white/10 w-full max-w-lg rounded-[2.5rem] p-8 lg:p-12 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-black italic uppercase">Order Confirmation</h2>
                <button type="button" onclick="closeModal()" class="text-white/40 hover:text-white text-3xl font-light focus:outline-none">&times;</button>
            </div>

        

                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-20 object-cover rounded-xl">
                    <div>
                        <h4 class="font-bold uppercase text-sm tracking-tight leading-none mb-1">{{ $product->title }}</h4>
                        <p class="text-blue-500 font-black italic">Rp {{ number_format($product->price) }}</p>
                    </div>
                </div>

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Tombol Beli di Halaman Detail --}}
    <form action="{{ route('cart.checkout', $product->id) }}" method="POST">
    @csrf
    <button type="submit" 
        class="w-full bg-blue-600 hover:bg-blue-500 text-white font-black italic uppercase py-5 rounded-2xl transition-all shadow-xl shadow-blue-600/20 active:scale-95 text-xl tracking-tighter">
        BELI SEKARANG
    </button>
</form>

                <p class="text-center text-white/20 text-[9px] uppercase tracking-[0.2em]">
                    Instant Delivery after confirmation
                </p>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('checkoutModal');
        const form = document.getElementById('checkoutForm');
        const submitBtn = document.getElementById('submitBtn');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        form.onsubmit = function() {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            submitBtn.innerHTML = 'Processing...';
            return true;
        };

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>