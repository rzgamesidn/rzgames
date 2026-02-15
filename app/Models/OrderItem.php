<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // 1. Daftarkan kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // 2. Relasi ke Product (PENTING biar bisa ambil gambar & judul game)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 3. Relasi balik ke Order (Opsional tapi bagus buat ada)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}