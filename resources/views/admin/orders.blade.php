<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZ Games | Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020205; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #020205; }
        ::-webkit-scrollbar-thumb { background: #1e3a8a; border-radius: 10px; }
    </style>
</head>
<body class="antialiased text-white overflow-x-hidden">

    <div class="min-h-screen relative flex flex-col">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-blue-600/10 blur-[120px] pointer-events-none"></div>

        <nav class="relative z-10 border-b border-white/5 bg-black/40 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="https://laravel.com/img/logomark.static.svg" class="w-8 h-8 opacity-80 shadow-[0_0_15px_rgba(255,255,255,0.2)]" alt="Logo">
                    <span class="text-xs font-black tracking-[0.3em] opacity-40 uppercase ml-2">Terminal / Admin</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="/dashboard" class="text-[10px] font-black uppercase tracking-widest text-white/40 hover:text-white transition-colors">Back to Base</a>
                    <div class="h-8 w-[1px] bg-white/10"></div>
                    <span class="text-[11px] font-bold italic text-blue-400 underline underline-offset-8 decoration-blue-500/30 uppercase">Commander: {{ Auth::user()->name ?? 'Operator' }}</span>
                </div>
            </div>
        </nav>

        <main class="relative z-10 flex-grow py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-600 w-10 h-[2px]"></div>
                            <span class="text-[10px] font-black text-blue-500 uppercase tracking-[0.5em]">Live Data Stream</span>
                        </div>
                        <h1 class="text-5xl font-black italic tracking-tighter uppercase">
                            Order <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-500 to-cyan-400">Command Center</span>
                        </h1>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex items-center gap-4 backdrop-blur-sm">
                        <div class="h-3 w-3 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60">System Core Active</span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-5 bg-green-500/10 border-l-4 border-green-500 text-green-400 rounded-r-2xl font-bold flex items-center gap-4">
                        <span class="text-xl">⚡</span>
                        <span class="text-sm tracking-wide">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-[#050508]/60 backdrop-blur-2xl rounded-[3rem] border border-white/10 shadow-2xl overflow-hidden overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.02]">
                                <th class="px-10 py-8 text-[10px] font-black uppercase tracking-[0.3em] text-white/30 italic">Order Manifest</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase tracking-[0.3em] text-white/30 italic">Subscriber</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase tracking-[0.3em] text-white/30 italic">Value (IDR)</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase tracking-[0.3em] text-white/30 italic text-center">Protocol Status</th>
                                <th class="px-10 py-8 text-[10px] font-black uppercase tracking-[0.3em] text-white/30 italic text-center">Override</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($orders as $o)
                            <tr class="group hover:bg-blue-500/[0.03] transition-all duration-500">
                                <td class="px-10 py-7">
                                    <div class="flex flex-col gap-4">
                                        {{-- LOGIKA MULTI-PRODUCT (CART) --}}
                                        @if($o->orderItems && $o->orderItems->count() > 0)
                                            @foreach($o->orderItems as $item)
                                                <div class="flex items-center gap-4">
                                                    <div class="relative w-12 h-12 shrink-0">
                                                        <img src="{{ asset('storage/' . ($item->product->image ?? 'default.jpg')) }}" class="w-full h-full rounded-xl object-cover border border-white/10 shadow-lg">
                                                        <div class="absolute -top-2 -right-2 bg-blue-600 text-[8px] font-black px-1.5 py-0.5 rounded-md">x{{ $item->quantity }}</div>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-xs font-black text-white group-hover:text-blue-400 transition-colors uppercase italic italic tracking-tight">
                                                            {{ $item->product->title ?? 'DELETED_PRODUCT' }}
                                                        </span>
                                                        <span class="text-[8px] text-white/20 font-bold tracking-widest uppercase">ITEM_ID: {{ $item->product_id }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        {{-- LOGIKA SINGLE PRODUCT (BACKUP) --}}
                                        @elseif($o->product)
                                            <div class="flex items-center gap-4">
                                                <img src="{{ asset('storage/' . $o->product->image) }}" class="w-12 h-12 rounded-xl object-cover border border-white/10">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-black text-white italic uppercase">{{ $o->product->title }}</span>
                                                    <span class="text-[8px] text-white/20 font-bold uppercase">DIRECT_PURCHASE</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-24 py-2 rounded-lg bg-red-500/10 border border-red-500/20 text-center text-[8px] font-black text-red-500 italic">LOST_DATA_SIGNAL</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-10 py-7">
                                    <span class="text-xs font-medium text-white/50 font-mono tracking-tighter">{{ $o->customer_email }}</span>
                                </td>
                                <td class="px-10 py-7">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-black text-white tracking-tighter">Rp{{ number_format($o->total_price, 0, ',', '.') }}</span>
                                        <span class="text-[8px] font-black text-blue-500/50 uppercase tracking-widest mt-1 italic">Verified Value</span>
                                    </div>
                                </td>
                                <td class="px-10 py-7 text-center">
                                    @php
                                        $styles = [
                                            'processed' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
                                            'success'   => 'bg-green-500/10 text-green-400 border-green-500/30',
                                            'failed'    => 'bg-red-500/10 text-red-400 border-red-500/30',
                                            'default'   => 'bg-white/5 text-white/40 border-white/10'
                                        ];
                                        $currStyle = $styles[$o->status] ?? $styles['default'];
                                    @endphp
                                    <span class="{{ $currStyle }} text-[9px] px-5 py-2.5 rounded-full font-black uppercase tracking-[0.2em] border italic">
                                        {{ $o->status }}
                                    </span>
                                </td>
                                <td class="px-10 py-7">
                                    <form action="{{ route('admin.orders.update', $o->id) }}" method="POST" class="flex justify-center">
                                        @csrf @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" 
                                            class="bg-[#0f0f15] border border-white/10 text-[9px] font-black uppercase tracking-[0.1em] rounded-xl text-white/60 focus:text-white focus:ring-2 focus:ring-blue-600 py-3 px-5 cursor-pointer hover:border-white/30 transition-all outline-none appearance-none italic">
                                            <option value="processed" {{ $o->status == 'processed' ? 'selected' : '' }}>EXECUTE: PROCESS</option>
                                            <option value="success" {{ $o->status == 'success' ? 'selected' : '' }}>EXECUTE: SUCCESS</option>
                                            <option value="failed" {{ $o->status == 'failed' ? 'selected' : '' }}>EXECUTE: FAIL</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-40 text-center text-white/20 uppercase font-black tracking-widest italic">No Data Transmissions</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-16 border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 opacity-20">
                    <p class="text-[9px] font-black uppercase tracking-[0.5em]">RZ Games Dashboard System • Core v2.1.0</p>
                    <p class="text-[9px] font-black uppercase tracking-[0.5em]">© 2026 Admin Terminal Protocol</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>