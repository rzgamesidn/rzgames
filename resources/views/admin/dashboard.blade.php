<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RZGAMES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-[#0f172a] text-white antialiased font-sans">

    {{-- SIDEBAR MINI & NAV --}}
    <nav class="bg-gray-900/50 backdrop-blur-xl border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <h1 class="text-xl font-bold tracking-tighter">RZGAMES <span class="text-blue-500">ADMIN</span></h1>
            </div>
            <a href="{{ route('user.home') }}" class="text-sm font-bold text-gray-400 hover:text-white transition">Kembali ke Toko →</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        {{-- HEADER WELCOME --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-blue-600 to-indigo-800 p-10 mb-10 shadow-2xl shadow-blue-500/10">
            <div class="relative z-10">
                <h2 class="text-4xl font-black italic tracking-tight">HALO, ADMIN RZGAMES! 🚀</h2>
                <p class="text-blue-100 mt-2 font-medium">Panel kendali pusat untuk raih cuan maksimal hari ini.</p>
            </div>
            <i class="fa-solid fa-rocket absolute -right-10 -bottom-10 text-[15rem] text-white/10 -rotate-12"></i>
        </div>

        {{-- STATS SECTION --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            @php
                $stats = [
                    ['Total Produk', $totalProducts, '🎮', 'from-orange-500 to-red-500'],
                    ['Total Orders', $totalOrders, '🛒', 'from-blue-500 to-indigo-500'],
                    ['Total Users', $totalUsers, '👥', 'from-emerald-500 to-teal-500'],
                    ['Revenue', 'Rp '.number_format($revenue), '💰', 'from-purple-500 to-pink-500'],
                ];
            @endphp
            @foreach($stats as $s)
            <div class="bg-gray-900/50 border border-gray-800 p-6 rounded-3xl hover:border-gray-700 transition">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $s[3] }} flex items-center justify-center text-xl shadow-lg">
                        {{ $s[2] }}
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">{{ $s[0] }}</p>
                        <h3 class="text-2xl font-black mt-1">{{ $s[1] }}</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- MENU UTAMA --}}
        <h3 class="text-xs font-black text-blue-500 uppercase tracking-[0.3em] mb-6">Menu Navigasi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Menu Card --}}
            <a href="{{ route('admin.add') }}" class="group bg-gray-900 border border-gray-800 p-8 rounded-[2rem] hover:bg-blue-600 transition-all duration-500 shadow-xl">
                <i class="fa-solid fa-plus-circle text-3xl mb-6 text-blue-500 group-hover:text-white transition"></i>
                <h4 class="text-xl font-bold group-hover:translate-x-2 transition-transform">Add Produk</h4>
                <p class="text-gray-500 text-sm mt-2 group-hover:text-blue-100 transition">Upload game original baru.</p>
            </a>

            <a href="{{ route('admin.list') }}" class="group bg-gray-900 border border-gray-800 p-8 rounded-[2rem] hover:bg-indigo-600 transition-all duration-500 shadow-xl">
                <i class="fa-solid fa-table-list text-3xl mb-6 text-indigo-500 group-hover:text-white transition"></i>
                <h4 class="text-xl font-bold group-hover:translate-x-2 transition-transform">List Produk</h4>
                <p class="text-gray-500 text-sm mt-2 group-hover:text-indigo-100 transition">Kelola katalog yang tayang.</p>
            </a>

            <a href="{{ route('admin.orders') }}" class="group bg-gray-900 border border-gray-800 p-8 rounded-[2rem] hover:bg-emerald-600 transition-all duration-500 shadow-xl">
                <i class="fa-solid fa-cart-shopping text-3xl mb-6 text-emerald-500 group-hover:text-white transition"></i>
                <h4 class="text-xl font-bold group-hover:translate-x-2 transition-transform">Orders</h4>
                <p class="text-gray-500 text-sm mt-2 group-hover:text-emerald-100 transition">Pantau transaksi masuk.</p>
            </a>

            <a href="{{ route('admin.account') }}" class="group bg-gray-900 border border-gray-800 p-8 rounded-[2rem] hover:bg-purple-600 transition-all duration-500 shadow-xl">
                <i class="fa-solid fa-wallet text-3xl mb-6 text-purple-500 group-hover:text-white transition"></i>
                <h4 class="text-xl font-bold group-hover:translate-x-2 transition-transform">Revenue</h4>
                <p class="text-gray-500 text-sm mt-2 group-hover:text-purple-100 transition">Cek saldo & penarikan.</p>
            </a>
        </div>
    </main>

</body>
</html>