<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Purchases;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function purchases()
    {
        return $this->hasMany(Purchases::class, 'customer_id');
    }
    
    
}
