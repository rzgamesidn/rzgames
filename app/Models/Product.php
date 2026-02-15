<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tambahkan 'priority' ke dalam array fillable
    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'original_price',
        'game_link',
        'drive_link',
        'is_trending',
        'priority', // Kunci biar urutan manual bisa disimpan!
    ];

    /**
     * Casting data biar Laravel otomatis tau tipe datanya
     */
    protected $casts = [
        'is_trending' => 'boolean',
        'priority' => 'integer', // Pastikan dibaca sebagai angka bulat
        'price' => 'decimal:0', // Pakai 0 kalau nggak mau ada angka di belakang koma (Rp 70.000)
        'original_price' => 'decimal:0',
    ];
}