<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Ambil data produk dari database
        $products = Product::select('name', 'stock')->get();
        

        // Dummy data penjualan per tanggal (bar chart)
        $sales = [
            'labels' => [
              
                '07 April 2025','08 April 2025','09 April 2025','10 April 2025'
            ],
            'data' => [
         
                100, 105, 108, 110, 112, 115, 118, 120
            ]
        ];

        return view('dashboard.index', compact('products', 'sales'));
    }
}
