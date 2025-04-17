<?php

namespace App\Http\Controllers;

// app/Http/Controllers/ProductController.php
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::latest()->paginate(5); // 5 per halaman
        return view('Product.index', compact('data'));
    }

    public function create()
    {
        return view('Product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'stock'  => 'required|integer|min:0',
            'image'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        // Proses upload gambar
        $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('images'), $imageName);
        // Storage::move('Product/'.$imageName,$request->image);
    
        // Simpan ke database hanya SEKALI
        Product::create([
            'name'  => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imageName, // path relatif ke /storage
        ]);
    
        return redirect()->route('Product.index')->with('success', 'Produk berhasil ditambahkan!');
    }
    

    


}

