<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran | RZGames</title>
    {{-- 1. WAJIB ADA: CSRF TOKEN UNTUK SECURITY --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;1,800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#050505] text-white min-h-screen overflow-x-hidden">

    {{-- DYNAMIC BACKGROUND --}}
    <div class="fixed inset-0 z-0 pointer-events-none">
        @if(isset($cartItems) && $cartItems->count() > 0)
            <img src="{{ asset('storage/' . $cartItems->first()->product->image) }}" class="w-full h-full object-cover opacity-10 blur-[100px] scale-125">
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#050505]/80 to-[#050505]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl mx-auto px-4 py-8">
        {{-- HEADER --}}
        <div class="flex items-center gap-4 mb-10">
            <a href="/" class="p-2.5 bg-white/5 border border-white/10 rounded-full hover:bg-white/10 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-black italic uppercase tracking-tighter italic">Konfirmasi</h1>
        </div>

        <div class="flex flex-col gap-6">
            {{-- BOX 1: ITEM PESANAN --}}
            <div class="bg-white/5 border border-white/10 rounded-[2rem] p-6 backdrop-blur-2xl shadow-2xl">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-[0.3em] mb-5">Item Pesanan</p>
                <div class="space-y-4">
                    @if(isset($cartItems) && $cartItems->count() > 0)
                        @foreach($cartItems as $item)
                            <div class="flex items-center gap-5 p-3 bg-white/5 rounded-2xl border border-white/5">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-14 h-20 object-cover rounded-xl border border-white/10 shadow-lg">
                                <div>
                                    <h2 class="text-sm font-black italic uppercase tracking-tight">{{ $item->product->title }}</h2>
                                    <p class="text-[9px] font-bold text-blue-400 uppercase mt-1">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-[9px] text-white/40 italic">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-xs text-white/30 italic">Gak ada item pesanan, Bang.</p>
                    @endif
                </div>
            </div>

            {{-- BOX 2: DATA PENERIMA --}}
            <div class="bg-white/5 border border-white/10 rounded-[2rem] p-6 backdrop-blur-2xl shadow-2xl">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-[0.3em] mb-5">Data Penerima</p>
                <div class="space-y-4">
                    <div class="relative group">
                        <label class="block text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2 ml-1">Email Aktif Kamu</label>
                        <input type="email" id="email_pembeli" 
                            value="{{ $order->customer_email != '-' ? $order->customer_email : (Auth::check() ? Auth::user()->email : '') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-sm font-medium focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-white/20"
                            placeholder="nama@email.com">
                    </div>
                </div>
            </div>

            {{-- BOX 3: RINGKASAN --}}
            <div class="bg-blue-600/10 border border-blue-500/20 rounded-[2.5rem] p-8 backdrop-blur-3xl shadow-2xl relative overflow-hidden">
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-[0.3em] mb-8">Ringkasan Pembayaran</p>
                <div class="space-y-4 mb-10">
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-white/40 font-bold uppercase text-[10px] tracking-widest">Harga Produk</span>
                        <span class="text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-6 border-t border-white/10 flex justify-between items-end">
                        <span class="text-xs font-black uppercase italic tracking-tighter text-blue-400">Total Tagihan</span>
                        <span class="text-3xl font-black italic tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="button" onclick="payNow()" class="group w-full relative h-16 bg-blue-600 hover:bg-blue-500 rounded-2xl font-black italic uppercase tracking-[0.2em] transition-all active:scale-[0.97] shadow-2xl shadow-blue-600/30 overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-3">Bayar Sekarang</span>
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
    function payNow() {
        const emailInput = document.getElementById('email_pembeli');
        const email = emailInput.value.trim();
        const orderId = '{{ $order->id }}';
        const snapToken = '{{ $snapToken }}';

        if (!email) {
            alert('Waduh Bang, isi email aktif dulu!');
            return;
        }

        // 2. PERBAIKAN FETCH: TAMBAH HEADER ACCEPT & CSRF DARI META TAG
        fetch("/update-email-order/" + orderId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                window.snap.pay(snapToken, {
                    onSuccess: function (result) { window.location.href = "/order-success/" + orderId; },
                    onPending: function (result) { alert("Menunggu pembayaran Bang!"); },
                    onError: function (result) { alert("Pembayaran Gagal!"); }
                });
            } else {
                alert('Gagal simpan email: ' + data.message);
            }
        })
        .catch(err => {
            console.error('Error Detail:', err);
            alert('Ada masalah koneksi/server. Cek Console (F12) Bang!');
        });
    }
    </script>
</body>
</html>