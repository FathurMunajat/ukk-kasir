<?php

namespace App\Http\Controllers;

use App\Models\DetailPurchases;
use Illuminate\Http\Request;

class DetailPurchasesController extends Controller
{
    public function index()
    {
        $details = DetailPurchases::with(['product', 'purchases'])->latest()->paginate(10);
        return view('DetailPurchase.index', compact('details'));
    }

    // Tambah method lain seperti show, delete, dll kalau dibutuhkan
}
