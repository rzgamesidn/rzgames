<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZGAMES - History Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .image-stack:hover img { margin-right: 0.5rem; transform: rotate(0deg); }
    </style>
</head>
<body class="bg-gray-950 text-white antialiased">
    <nav class="bg-gray-900 border-b border-gray-800 p-6 mb-12">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="{{ route('user.home') }}" class="font-black italic text-2xl text-blue-500 hover:text-blue-400 transition">RZGAMES</a>
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Library Game Kamu</span>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 pb-20">
        <h2 class="text-3xl font-black italic mb-8 border-l-4 border-blue-600 pl-4 uppercase tracking-tighter">Riwayat Pembelian</h2>

        @if($orders->isEmpty())
            <div class="text-center py-20 bg-gray-900 rounded-[2rem] border-2 border-dashed border-gray-800">
                <p class="text-gray-500 font-bold italic uppercase tracking-widest mb-4">Belum ada game yang dibeli Bang!</p>
                <a href="{{ route('user.home') }}" class="text-blue-500 hover:text-blue-400 underline font-bold uppercase text-xs transition">Ayo Belanja Sekarang</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    @php $firstItem = $order->orderItems->first(); @endphp

                    <div class="bg-gray-900 p-6 rounded-[2rem] border border-gray-800 hover:border-blue-500/50 transition shadow-2xl mb-6">
                        {{-- HEADER CARD: GAMBAR & JUDUL --}}
                        <div class="flex flex-col md:flex-row items-center gap-6 mb-6">
                            <div class="flex items-center -space-x-12 hover:space-x-2 transition-all duration-500 image-stack">
                                @foreach($order->orderItems as $item)
                                    @if($item->product)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             class="w-24 h-32 object-cover rounded-xl shadow-2xl border-2 border-gray-900 transition-all duration-300 hover:scale-110 hover:z-50 first:rotate-[-5deg] last:rotate-[5deg] hover:rotate-0"
                                             title="{{ $item->product->title }}">
                                    @endif
                                @endforeach
                            </div>
                            
                            <div class="flex-1 w-full text-left">
                                <div class="flex justify-between items-start w-full">
                                    <h4 class="text-xl font-extrabold uppercase tracking-tight text-white">
                                        @if($order->orderItems->count() > 1)
                                            {{ $firstItem->product->title ?? 'Game' }} & {{ $order->orderItems->count() - 1 }} More
                                        @else
                                            {{ $firstItem->product->title ?? 'Game Title' }}
                                        @endif
                                    </h4>
                                    
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ in_array($order->status, ['success', 'paid', 'processed']) ? 'bg-green-500/20 text-green-500' : 'bg-yellow-500/20 text-yellow-500' }}">
                                        {{ in_array($order->status, ['paid', 'processed']) ? 'SUCCESS' : strtoupper($order->status) }}
                                    </span>
                                </div>
                                <p class="text-gray-500 text-[10px] font-bold mt-1 uppercase">ORDER ID: #{{ $order->order_id }}</p>
                                <p class="text-gray-500 text-[10px] font-bold uppercase italic mt-1">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- LINK DOWNLOAD & ACTIONS --}}
                        @if($order->status == 'success' || $order->status == 'processed')
                            <div class="flex flex-col gap-3">
                                {{-- 1. LOOP HANYA UNTUK LINK DOWNLOAD --}}
                                @foreach($order->orderItems as $item)
                                    @if($item->product && $item->product->drive_link)
                                        <div class="bg-blue-600/10 border border-blue-600/20 p-4 rounded-2xl flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3 overflow-hidden text-left">
                                                <span class="text-2xl">☁️</span>
                                                <div class="overflow-hidden">
                                                    <p class="text-blue-400 text-[10px] font-black uppercase tracking-tighter">{{ $item->product->title }}</p>
                                                    <p class="text-gray-300 text-xs truncate max-w-[150px] md:max-w-md">{{ $item->product->drive_link }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ $item->product->drive_link }}" target="_blank" class="shrink-0 bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-xl text-xs font-black shadow-lg shadow-blue-600/20 transition">
                                                DOWNLOAD
                                            </a>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- 2. TOMBOL ACTION (CUKUP SEKALI DI LUAR LOOP ITEM) --}}
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('order.success', $order->id) }}" class="flex-1 text-center py-2 border border-gray-800 rounded-xl text-[10px] font-bold text-gray-500 hover:bg-white/5 transition uppercase tracking-widest">
                                        Detail
                                    </a>
                                    <a href="{{ route('order.download_invoice', $order->id) }}" class="flex-1 text-center py-2 bg-red-600/10 border border-red-600/20 rounded-xl text-[10px] font-bold text-red-500 hover:bg-red-600 hover:text-white transition uppercase tracking-widest">
                                        📄 Download PDF
                                    </a>
                                </div>

                                <a href="{{ route('order.success', $order->id) }}" class="text-center py-2 border border-gray-800 rounded-xl text-[10px] font-bold text-gray-500 hover:bg-white/5 transition uppercase tracking-widest">
                                    Lihat Detail Pembayaran & Invoice
                                </a>
                            </div>
                        @else
                            <div class="bg-gray-800/50 p-4 rounded-2xl flex items-center gap-3">
                                <span class="animate-pulse text-xl">⏳</span>
                                <p class="text-gray-400 text-xs font-bold italic text-left">Pembayaran sedang diproses atau menunggu konfirmasi Admin...</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>