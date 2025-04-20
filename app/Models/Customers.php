<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\purchases;

class Customers extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'poin',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchases::class, 'customer_id');
    }
}
