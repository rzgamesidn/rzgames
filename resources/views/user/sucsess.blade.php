<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success - RZGAMES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-[#050505] text-white antialiased font-['Plus_Jakarta_Sans']">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-2xl w-full bg-[#0f0f0f] border border-green-500/30 p-8 md:p-16 rounded-[3.5rem] text-center shadow-2xl shadow-green-500/10">
            
            {{-- Icon Success --}}
            <div class="w-24 h-24 bg-green-500/10 border border-green-500/20 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <span class="text-5xl">✅</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-black italic uppercase tracking-tighter mb-2">Transaction Success!</h1>
            <p class="text-white/40 text-[10px] md:text-xs font-bold uppercase tracking-[0.3em] mb-12">Thank you for your purchase</p>

            <div class="bg-white/5 border border-white/10 p-6 md:p-10 rounded-[2.5rem] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 blur-[50px] rounded-full"></div>
                
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em] mb-6 text-center">Your Purchase Today</p>

                    <div class="space-y-4">
                        {{-- SITUASI A: JIKA BELI LEWAT KERANJANG (LOOPING SEMUA GAME) --}}
                        @if(isset($cartItems) && $cartItems->count() > 0)
                            @foreach($cartItems as $item)
                                <div class="bg-gray-900/50 border border-white/5 p-4 rounded-2xl flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-16 object-cover rounded-lg shadow-lg border border-white/10">
                                    <div class="text-left flex-1">
                                        <h4 class="text-blue-400 text-sm font-black uppercase italic leading-tight">{{ $item->product->title }}</h4>
                                        <p class="text-[9px] text-white/40 uppercase tracking-widest mt-1">Status: Terbayar (Qty: {{ $item->quantity }})</p>
                                    </div>
                                    
                                    @if($item->product->drive_link)
                                        <a href="{{ $item->product->drive_link }}" target="_blank" 
                                           class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-[9px] font-black transition-all">
                                            DOWNLOAD
                                        </a>
                                    @else
                                        <span class="text-white/20 text-[9px] italic">No Link</span>
                                    @endif
                                </div>
                            @endforeach

                        {{-- SITUASI B: JIKA BELI SATUAN (DIRECT CHECKOUT) --}}
                        @elseif(isset($order) && $order->product)
                            <div class="bg-gray-900/50 border border-white/5 p-4 rounded-2xl flex items-center gap-4">
                                <img src="{{ asset('storage/' . $order->product->image) }}" class="w-12 h-16 object-cover rounded-lg shadow-lg border border-white/10">
                                <div class="text-left flex-1">
                                    <h4 class="text-blue-400 text-sm font-black uppercase italic leading-tight">{{ $order->product->title }}</h4>
                                    <p class="text-[9px] text-white/40 uppercase tracking-widest mt-1">Status: Terbayar</p>
                                </div>
                                
                                @if($order->product->drive_link)
                                    <a href="{{ $order->product->drive_link }}" target="_blank" 
                                       class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl text-[9px] font-black transition-all">
                                        DOWNLOAD
                                    </a>
                                    
                                @else
                                    <span class="text-white/20 text-[9px] italic">No Link</span>
                                @endif
                            </div>

                        @else
                            <div class="py-8">
                                <p class="text-center text-white/20 text-xs italic">Gak ada data transaksi, Bang.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Warning Box --}}                   
                    <div class="mt-6 flex flex-col gap-3">
                        <a href="{{ route('order.download_invoice', $order->id) }}" 
                        class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-3 rounded-2xl font-black italic uppercase tracking-widest transition shadow-lg shadow-red-600/20">
                            <span>📄</span> DOWNLOAD INVOICE (PDF)
                        </a>
</div>
                </div>
            </div> 

            {{-- Back Button --}}
            <div class="mt-12">
                <a href="{{ route('user.home') }}" class="inline-flex items-center gap-3 text-xs font-bold text-white/30 hover:text-white uppercase tracking-[0.2em] transition-all group">
                    <span class="group-hover:-translate-x-2 transition-transform duration-300">←</span> Back to Store Home
                </a>
            </div>
        </div> 
    </div>

</body>
</html>