<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // FIX image_6a313f: Izinin kolom ini diisi
    protected $fillable = ['user_id', 'product_id']; // Fix MassAssignment

public function product() {
    return $this->belongsTo(Product::class); // Fix InvalidArgumentException
}
}