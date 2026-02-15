<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Carts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Midtrans\Notification;

class AdminController extends Controller
{
    /**
     * 1. Dashboard Admin
     * Memperbaiki error "unexpected return" (image_519290.png)
     */
    public function index() {
    try {
        $totalProducts = Product::count();
        $totalOrders   = Order::count();
        $totalUsers    = User::where('role', 'user')->count();
        
        // Revenue dari status success DAN processed (biar akurat)
        $revenue = Order::whereIn('status', ['success', 'processed'])->sum('total_price');
        
        $recentOrders   = Order::latest()->take(5)->get();
        $latestProducts = Product::latest()->take(5)->get();

        // TAMBAHAN: Ambil data produk yang lagi Trending biar bisa dipantau di dashboard
        $trendingNow = Product::where('is_trending', 1)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalUsers', 'revenue', 
            'recentOrders', 'latestProducts', 'trendingNow'
        ));
    } catch (\Exception $e) {
        return "Waduh Bang, ada masalah di database: " . $e->getMessage();
    }
}

    /**
     * 2. List Produk
     * Menyesuaikan view dari admin.products.list ke admin.list (image_51a1d5.png)
     */
 public function listProducts(Request $request) {
    $search = $request->input('search');

    $products = Product::query()
        ->when($search, function($query) use ($search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })
        ->orderBy('priority', 'desc') // 1. Prioritas utama: Angka paling gede di atas
        ->orderBy('created_at', 'desc') // 2. Baru kemudian urut tanggal terbaru
        ->paginate(18);

    return view('admin.list', compact('products'));
}

    /**
     * 3. Halaman Tambah Produk
     * FIX error "View [admin.products.add] not found" (image_51a93d.png)
     */
    public function addProduct() {
    // Hapus '.products', arahkan langsung ke folder admin
    return view('admin.add'); 
}

    /**
     * 4. Proses Simpan Produk
     */
public function store(Request $request)
{
    // 1. Validasi - Tambahkan 'priority' biar bisa input angka
    $request->validate([
        'image'          => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
        'title'          => 'required|string|max:255',
        'description'    => 'required',
        'drive_link'     => 'required|url',
        'price'          => 'required|numeric',
        'original_price' => 'nullable|numeric',
        'game_link'      => 'nullable|url',
        'priority'       => 'nullable|numeric', // Validasi kolom baru
    ]);

    // 2. Upload Gambar
    $path = null;
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
    }

    // 3. Simpan ke Database
    Product::create([
        'image'          => $path,
        'title'          => $request->title,
        'description'    => $request->description,
        'game_link'      => $request->game_link, 
        'drive_link'     => $request->drive_link,
        'price'          => $request->price,
        'original_price' => $request->original_price,
        'is_trending'    => $request->has('is_trending') ? 1 : 0, 
        'priority'       => $request->priority ?? 0, // Kalau kosong, otomatis 0
    ]);

    return redirect()->route('admin.list')->with('success', '🚀 Game Berhasil Ditambah & Urutan Diatur!');
}

    /**
     * 5. Halaman Edit Produk
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit', compact('product'));
    }

    /**
     * 6. Proses Update Produk
     */
 public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    // 1. Validasi - Tambahkan priority ke list validasi
    $request->validate([
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240', 
        'title'       => 'required|string|max:255',
        'description' => 'required',
        'drive_link'  => 'required|url',
        'price'       => 'required|numeric',
        'priority'    => 'nullable|numeric', // Tambah ini Bang
    ]);

    // 2. Ambil data teks - Tambahkan priority ke list update
    $data = $request->only([
        'title', 'description', 'game_link', 'drive_link', 'price', 'original_price', 'priority'
    ]);

    // 3. Handle checkbox trending biar gak error di database
    $data['is_trending'] = $request->has('is_trending') ? 1 : 0;
    
    // Pastikan priority ada isinya (default 0 kalau dikosongin)
    $data['priority'] = $request->priority ?? 0;

    // 4. Logic Upload Gambar
    if ($request->hasFile('image')) {
        // Hapus foto lama biar gak menuh-menuhin hosting
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }
        
        // Simpan foto baru
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    // 5. Update data ke database
    $product->update($data);

    // 6. Redirect balik ke list
    return redirect()->route('admin.list')->with('success', '✅ Data Game Berhasil Diperbarui & Urutan Disinkronkan!');
}

    /**
     * 7. Hapus Produk
     */
    public function deleteProduct($id) {
    try {
        $product = Product::findOrFail($id);

        // 1. Hapus Foto dari Storage (Biar gak nyampah)
        // Tambahkan backslash \ sebelum Storage biar gak error 'Class not found'
        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        // 2. Hapus Produk dari Database
        $product->delete();

        // 3. BERSIHKAN CACHE (Ini kunci biar gak nyangkut di Trending)
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');

        return back()->with('success', '🗑️ Produk & file gambar berhasil dihapus total, Bang!');
        
    } catch (\Illuminate\Database\QueryException $e) {
        // Jika produk sudah ada di tabel Order, mending is_trending nya aja yang dimatiin
        $product = Product::find($id);
        $product->update(['is_trending' => 0]);
        
        return back()->with('error', 'Gak bisa dihapus total karena sudah ada di riwayat order user, tapi sudah saya hilangkan dari Trending Bang!');
    }
}

    /**
     * 8. Halaman Pesanan
     */
    public function orders() {
    // Kita ambil semua yang statusnya 'processed' DAN 'success'
    // Biar data yang baru masuk (processed) tetep kelihatan Bang
    $orders = Order::with(['orderItems.product'])
                    ->whereIn('status', ['processed', 'success']) 
                    ->latest()
                    ->paginate(15);

    return view('admin.orders', compact('orders'));
}
    /**
     * 9. Halaman Keuangan
     */
    public function account()
{
    // Kita ambil data order biar tabel di bawahnya nggak error
    $orders = \App\Models\Order::with('product')->latest()->get();
    
    // Hitung total pendapatan (status success saja)
    $totalRevenue = \App\Models\Order::where('status', 'success')->sum('total_price');

    return view('admin.account', compact('orders', 'totalRevenue'));
}

public function callback(Request $request)
{
    $serverKey = env('MIDTRANS_SERVER_KEY');
    $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    // Validasi biar bukan notifikasi palsu
    if ($hashed == $request->signature_key) {
        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            // OTOMATIS GANTI STATUS DI SINI
            $order = Order::where('order_id', $request->order_id)->first();
            if ($order) {
                $order->update(['status' => 'success']); // Langsung otomatis!
            }
        }
    }
}

public function updateStatus(Request $request, $id)
{
    // Cari order berdasarkan ID
    $order = \App\Models\Order::findOrFail($id);
    
    // Update status dari request (processed/success/failed)
    $order->update([
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', 'SYSTEM: Protocol Updated to ' . strtoupper($request->status));
}




}