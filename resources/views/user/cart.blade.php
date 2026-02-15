<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZGAMES - Checkout Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#050505] text-white antialiased selection:bg-blue-500">
    
    {{-- Header Keranjang --}}
    <nav class="bg-black/40 backdrop-blur-md border-b border-white/5 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 h-24 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.home') }}" class="group">
                    <span class="font-black italic text-3xl tracking-tighter uppercase group-hover:text-blue-500 transition-colors">RZ<span class="text-blue-600">GAMES</span></span>
                </a>
                <div class="h-8 w-px bg-white/10 mx-2"></div>
                <span class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500"></span>
            </div>
            <a href="{{ route('user.home') }}" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-all flex items-center gap-2">
                <span>←</span> Back to Store
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-2 h-10 bg-blue-600 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.6)]"></div>
            <h2 class="text-4xl font-black italic uppercase tracking-tighter">Your <span class="text-blue-500">Inventory</span></h2>
        </div>

        @if(count($cartItems) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- List Item Section --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                    <div class="group relative flex items-center justify-between bg-white/5 border border-white/5 p-5 rounded-3xl hover:bg-white/[0.08] hover:border-white/10 transition-all duration-300">
                        <div class="flex items-center gap-6">
                            {{-- Image Container --}}
                            <div class="relative overflow-hidden w-24 h-28 rounded-2xl shadow-2xl">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            </div>
                            
                            <div>
                                <h4 class="text-xl font-black italic uppercase tracking-tight text-white mb-1">{{ $item->product->title }}</h4>
                                <div class="flex items-center gap-3">
                                    <span class="text-blue-500 font-bold tracking-tighter text-lg">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                    <span class="px-2 py-0.5 rounded bg-white/10 text-[10px] font-black uppercase">x{{ $item->quantity }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-4">
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="submit" class="p-3 rounded-2xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Summary Section --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-32 bg-gradient-to-br from-blue-700 to-indigo-900 p-8 rounded-[2.5rem] shadow-[0_20px_50px_rgba(37,99,235,0.3)] relative overflow-hidden">
                        {{-- Ornamen --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 blur-[50px] rounded-full -mr-16 -mt-16"></div>
                        
                        <h3 class="relative z-10 text-xs font-black uppercase tracking-[0.2em] text-blue-200 mb-6">Payment Summary</h3>
                        
                        <div class="relative z-10 flex justify-between items-end mb-8">
                            <span class="text-sm font-bold uppercase text-blue-100 opacity-70 italic">Total Bill:</span>
                            <div class="text-right">
                                <span class="block text-4xl font-black tracking-tighter">
                                    Rp {{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        @auth
                            <form action="{{ route('payment.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="total_price" value="{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}">
                                <button type="submit" class="relative z-10 block w-full bg-white text-blue-700 py-5 rounded-2xl font-bold">
                                    Pay Now ⚡
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="relative z-10 block w-full bg-black text-white py-5 rounded-2xl text-center font-black uppercase tracking-widest hover:bg-gray-900 transition-all text-sm">
                                Login to Purchase
                            </a>
                        @endauth

                        <p class="relative z-10 mt-6 text-center text-[10px] text-blue-200/50 uppercase font-bold tracking-widest">
                            Secure instan Payment
                        </p>
                    </div>
                </div>
            </div>

        @else
            {{-- Empty State Jauh Lebih Cakep --}}
            <div class="flex flex-col items-center justify-center py-24 bg-white/[0.02] border-2 border-dashed border-white/5 rounded-[3rem]">
                <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 text-5xl">🛒</div>
                <h3 class="text-2xl font-black italic uppercase tracking-tighter mb-2">Keranjang Kosong, Bang!</h3>
                <p class="text-gray-500 font-medium mb-8">Cari game impianmu dan mulai isi inventory ini.</p>
                <a href="{{ route('user.home') }}" class="bg-blue-600 px-8 py-3 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-blue-500 transition-all">
                    Ayo Belanja!
                </a>
            </div>
        @endif
    </main>

</body>
</html>