<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RZ Games | Revenue Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020205; }
    </style>
</head>
<body class="antialiased text-white">
    <div class="min-h-screen relative flex flex-col">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-purple-600/10 blur-[120px] pointer-events-none"></div>

        {{-- Navigasi --}}
        <nav class="relative z-10 border-b border-white/5 bg-black/40 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black tracking-[0.3em] opacity-40 uppercase">Terminal / Financial</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-black uppercase tracking-widest text-white/40 hover:text-white transition-colors">Back to Base</a>
            </div>
        </nav>

        <main class="relative z-10 flex-grow py-12 px-6">
            <div class="max-w-7xl mx-auto">
                
                {{-- Revenue Header --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="md:col-span-2 bg-gradient-to-br from-purple-600/20 to-blue-600/5 border border-white/10 rounded-[2.5rem] p-10 backdrop-blur-xl">
                        <span class="text-[10px] font-black text-purple-400 uppercase tracking-[0.4em]">Total Available Balance</span>
                        <h1 class="text-6xl font-black italic tracking-tighter mt-4">
                            Rp{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                        </h1>
                        <p class="text-white/30 text-xs mt-6 font-bold uppercase tracking-widest italic">Encrypted Secure Wallet Address: RZ-{{ Auth::id() }}-TX</p>
                    </div>
                    
                    <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-10 flex flex-col justify-center items-center text-center">
                        <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mb-4 border border-green-500/30">
                            <span class="text-2xl">💰</span>
                        </div>
                        <button class="w-full py-4 bg-white text-black rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-purple-500 hover:text-white transition-all duration-500 shadow-xl shadow-white/5">
                            Withdraw Funds
                        </button>
                    </div>
                </div>

                {{-- Recent Payouts / History --}}
                <h2 class="text-xs font-black uppercase tracking-[0.5em] text-white/20 mb-6 italic ml-4">Recent Financial Logs</h2>
                <div class="bg-[#050508]/60 backdrop-blur-2xl rounded-[3rem] border border-white/10 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-white/[0.02] border-b border-white/5">
                            <tr>
                                <th class="px-10 py-6 text-[9px] font-black uppercase tracking-widest text-white/30">Source</th>
                                <th class="px-10 py-6 text-[9px] font-black uppercase tracking-widest text-white/30">Amount</th>
                                <th class="px-10 py-6 text-[9px] font-black uppercase tracking-widest text-white/30">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($orders as $o)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="px-10 py-6 font-mono text-xs text-white/60">PAYMENT_FROM_{{ strtoupper(explode('@', $o->customer_email)[0]) }}</td>
                                <td class="px-10 py-6 font-black text-sm tracking-tighter">+Rp{{ number_format($o->total_price, 0, ',', '.') }}</td>
                                <td class="px-10 py-6">
                                    <span class="text-[8px] font-black px-3 py-1 bg-green-500/10 text-green-500 border border-green-500/20 rounded-full uppercase">Success</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-20 text-center opacity-20 text-[10px] font-black uppercase tracking-widest">No transaction history detected</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>
</body>
</html>