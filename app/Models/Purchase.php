<?php

namespace App\Models;

use App\Models\User;
use App\Models\Member;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'purchase',
        'total_price',
        'total_pay',
        'total_return',
        'member_id',
        'user_id',
        'poin',
        'used_point',
    ];

    /**
     * Relasi ke member (jika ada)
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Relasi ke user (staff kasir)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke detail pembayaran (misalnya item-item produk yang dibeli)
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

public function products()
{
    return $this->belongsToMany(Product::class, 'purchase_product', 'purchase_id', 'product_id')
                ->withPivot('quantity', 'price'); // jika ada kolom tambahan seperti quantity dan price
}


}
