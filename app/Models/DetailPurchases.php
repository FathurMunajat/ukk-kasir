<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPurchases extends Model
{
    protected $fillable = [
        'purchases_id',
        'product_id',
        'amount',
        'price',
        'subtotal',
    ];
    /**
     * Relasi ke tabel purchases
     */
    public function product()
{
    return $this->belongsTo(Product::class);
}

public function purchases()
{
    return $this->belongsTo(Purchases::class, 'purchases_id');
}

}
