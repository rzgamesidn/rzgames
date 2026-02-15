<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class HomeController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('priority', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->get();

        // FIX: take(5) DIHAPUS biar semua produk trending (ke-6, ke-7, dst) muncul
        $trendingProducts = Product::where('is_trending', 1)
                                   ->orderBy('priority', 'desc')
                                   ->get(); 
        
        $favorites = Auth::check() ? Auth::user()->favorites()->pluck('product_id')->toArray() : [];
        
        return view('user.home', compact('products', 'trendingProducts', 'favorites'));
    }

    public function detail($id)
    {
        $product = Product::findOrFail($id);
        return view('user.detail', compact('product'));
    }

public function checkout(Request $request, $id = null)
{
    $customerEmail = Auth::check() ? Auth::user()->email : $request->input('email', 'guest@rzgames.com');
    $userId = Auth::id();
    $itemsToProcess = [];
    $totalPrice = 0;

    if (!$id) {
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();
        if ($cartItems->isEmpty()) return back()->with('error', 'Keranjang kosong!');

        foreach ($cartItems as $item) {
            $itemsToProcess[] = [
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price
            ];
            $totalPrice += ($item->product->price * $item->quantity);
        }
    } else {
        $product = Product::findOrFail($id);
        $itemsToProcess[] = [
            'product_id' => $product->id,
            'quantity'   => 1,
            'price'      => $product->price
        ];
        $totalPrice = $product->price;
    }

    $order = Order::create([
        'user_id'        => $userId,
        'customer_email' => $customerEmail,
        'total_price'    => $totalPrice,
        'status'         => 'pending',
        'order_id'       => 'RZG-' . strtoupper(bin2hex(random_bytes(3))),
    ]);

    foreach ($itemsToProcess as $item) {
        // Gunakan path lengkap App\Models\OrderItem jika belum di-import di atas
        \App\Models\OrderItem::create([
            'order_id'   => $order->id,
            'product_id' => $item['product_id'],
            'quantity'   => $item['quantity'],
            'price'      => $item['price']
        ]);
    }

    if (!$id && Auth::check()) {
        Cart::where('user_id', $userId)->delete();
    }
    
    return redirect()->route('user.payment', $order->id);
}

public function payment($id)
{
    // 1. Ambil order beserta rincian produknya (OrderItem)
    $order = Order::with(['orderItems.product'])->findOrFail($id);
    
    // 2. Gunakan data dari OrderItems untuk ditampilkan di Blade
    $cartItems = $order->orderItems; 

    // 3. Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
    \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
    \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');

    // 4. Buat Parameter Snap
    $params = [
        'transaction_details' => [
            'order_id' => $order->order_id . '-' . time(),
            'gross_amount' => (int)$order->total_price,
        ],
        'customer_details' => [
            'first_name' => Auth::check() ? Auth::user()->name : 'Guest',
            'email' => $order->customer_email,
        ],
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
    } catch (\Exception $e) {
        \Log::error('Midtrans Error: ' . $e->getMessage());
        $snapToken = null; 
    }

    return view('user.payment', compact('order', 'snapToken', 'cartItems'));
}

  public function checkoutSuccess($id)
{
    // 1. Ambil order beserta itemnya
    $order = Order::with('orderItems.product')->findOrFail($id);

    // 2. Update status jadi processed
    $order->update(['status' => 'processed']);

    // 3. Hapus keranjang user
    if (Auth::check()) {
        Cart::where('user_id', Auth::id())->delete(); 
    }

    // 4. Lempar ke view 'sucsess' (pastikan file blade-nya namanya 'sucsess.blade.php')
    return view('user.sucsess', [
        'order' => $order, 
        'cartItems' => $order->orderItems 
    ]);
}

   public function history() 
{
    // Kita panggil Order -> OrderItems -> Product agar data lengkap sampai ke gambar
    $history = \App\Models\Order::with(['orderItems.product']) 
                ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->whereIn('status', ['processed', 'success']) 
                ->latest()
                ->get();

    // Kirim ke view dengan variabel $orders agar sesuai dengan Blade abang
    return view('user.history', ['orders' => $history]);
}

public function downloadInvoice($id)
{
    // Ambil data order lengkap dengan item dan produknya
    $order = \App\Models\Order::with('orderItems.product')->findOrFail($id);

    // Cek apakah ini milik user yang login biar gak diintip orang lain
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }

    // Load view khusus invoice (kita buat di langkah 3)
    $pdf = Pdf::loadView('user.invoice_pdf', compact('order'));

    // Download filenya dengan nama sesuai Order ID
    return $pdf->download('Invoice-RZGAMES-'.$order->order_id.'.pdf');
}

    public function cart() {
    if (auth()->check()) {
        // Ambil data dari database jika user login
        $cartItems = \App\Models\Cart::where('user_id', auth()->id())->with('product')->get();
    } else {
        // Ambil data dari session jika tamu
        $sessionCart = session()->get('cart', []);
        
        // Transformasi agar formatnya sama dengan data database supaya Blade tidak error
        $cartItems = collect($sessionCart)->map(function ($details, $id) {
            return (object) [
                'id' => $id,
                'product' => (object) [
                    'id' => $id,
                    'title' => $details['title'],
                    'price' => $details['price'],
                    'image' => $details['image'],
                ],
                'quantity' => $details['quantity']
            ];
        });
    }
    return view('user.cart', compact('cartItems'));
}

  public function addToCart($id)
{
    $product = Product::findOrFail($id);

    if (auth()->check()) {
        // --- BAGIAN LOGIN (DATABASE) ---
        // Cari dulu barangnya udah ada belum di keranjang user
        $cart = Cart::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->first();

        if ($cart) {
            // Kalau udah ada, tinggal tambah 1 jumlahnya
            $cart->increment('quantity');
        } else {
            // Kalau belum ada, baru bikin baru
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $id,
                'quantity' => 1
            ]);
        }
    } else {
        // --- BAGIAN GUEST (SESSION) ---
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            // FIX: Karena Array, nambahnya pakai ++ bukan increment()
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "title"    => $product->title,
                "quantity" => 1,
                "price"    => $product->price,
                "image"    => $product->image
            ];
        }
        session()->put('cart', $cart);
    }

    return back()->with('success', '🚀 Game masuk keranjang, Bang!');
}

 public function favorites()
{
    if (auth()->check()) {
        // Jika Login: Ambil dari database dengan relasi product
        $favorites = \App\Models\Favorite::with('product')
            ->where('user_id', auth()->id())
            ->get();
    } else {
        // Jika Guest: Ambil ID dari session
        $sessionFavs = session()->get('favorites', []);

        // Ambil data produk asli berdasarkan ID di session
        $products = \App\Models\Product::whereIn('id', $sessionFavs)->get();

        // TRANSFORMASI: Kita bungkus produk ke dalam object 'product' 
        // supaya sinkron dengan panggilan $fav->product di Blade
        $favorites = $products->map(function ($product) {
            return (object) [
                'product' => $product
            ];
        });
    }

    return view('user.favorites', compact('favorites'));
}

 public function addToFavorite($id) {
    if (auth()->check()) {
        \App\Models\Favorite::firstOrCreate(['user_id' => auth()->id(), 'product_id' => $id]);
    } else {
        $favs = session()->get('favorites', []);
        if (!in_array($id, $favs)) {
            $favs[] = $id; // Masukkan ID ke array
            session()->put('favorites', $favs); // Simpan kembali ke session
        }
    }
    return back()->with('success', 'Game disukai!');
}


public function search(Request $request)
    {
        $query = $request->input('query');
        
        $products = Product::where('title', 'LIKE', "%{$query}%")
                           ->orderBy('priority', 'desc') // Tambah ini juga biar hasil search urut priority
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        // FIX: Di sini juga hapus take(5) nya Bang
        $trendingProducts = Product::where('is_trending', 1)
                                   ->orderBy('priority', 'desc')
                                   ->get();

        $favorites = Auth::check() ? Auth::user()->favorites()->pluck('product_id')->toArray() : [];

        return view('user.home', compact('products', 'trendingProducts', 'favorites'));
    }

public function removeFromCart(Request $request)
{
    if (auth()->check()) {
        Cart::where('id', $request->id)->where('user_id', auth()->id())->delete();
    } else {
        $cart = session()->get('cart', []);
        if(isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }
    }
    return back()->with('success', 'Barang dibuang, Bang!');
}

public function processCheckout(Request $request) {
    // 1. Simpan Order Utama
    $order = Order::create([
        'user_id' => auth()->id(),
        'total_price' => $request->total_price,
        'customer_email' => $request->email,
        'status' => 'pending'
    ]);

    // 2. AMBIL isi keranjang
    $cartItems = Cart::where('user_id', auth()->id())->get();

    // 3. PINDAHKAN ke OrderItems (Biar gak ilang pas cart didelete)
    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price
        ]);
    }

    // 4. BARU BOLEH DIHAPUS KERANJANGNYA
    Cart::where('user_id', auth()->id())->delete();

    return redirect()->route('checkout.success', $order->id);
}

public function showPayment($id) 
{
    $order = Order::findOrFail($id);
    
    // Ambil isi keranjang user yang lagi login
    $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

    return view('user.payment', compact('order', 'cartItems'));
}

// INI FUNGSI BARU YANG HARUS ADA BIAR GAK ERROR KONEKSI
public function updateEmailOrder(Request $request, $id)
{
    try {
        // 1. Pastikan model Order sudah di-import di atas atau pakai path lengkap
        $order = \App\Models\Order::findOrFail($id);
        
        // 2. Update email di database
        // Pastikan kolom 'customer_email' sudah ada (hasil php artisan migrate)
        $order->update([
            'customer_email' => $request->email
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diupdate'
        ]);
    } catch (\Exception $e) {
        // Log errornya biar kalau ada apa-apa bisa dicek di storage/logs/laravel.log
        \Log::error('Update Email Gagal: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal update: ' . $e->getMessage()
        ], 500);
    }
}



}