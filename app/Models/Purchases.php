<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchases extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'purchases_date',
        'total_price',
        'total_payment',
        'total_change',
        'poin',
        'total_poin',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function details()
    {
        return $this->hasMany(DetailPurchases::class, 'purchases_id');
    }
    
}
