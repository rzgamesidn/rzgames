<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'product_id', 
        'order_id', // WAJIB TAMBAH INI biar RZG-xxxx kesimpan
        'customer_email', 
        'total_price', 
        'status'
    ];

    // Relasi ke produk (Fallback untuk pembelian satuan)
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'title' => 'Multiple Products (Cart)',
            'image' => 'default.jpg'
        ]);
    }

    // DISESUAIKAN: Nama fungsi ganti jadi orderItems biar sinkron sama Controller kita
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}