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
    

    public function edit($id)
    {
        $item = Product::find($id);
        return view('Product.edit', compact('item'));
    }

    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name'  => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->only(['name', 'price', 'stock']);

    if ($request->hasFile('image')) {
        $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->storeAs('public/products', $imageName);
        $data['image'] = 'products/' . $imageName;
    }

    $product->update($data);

    return redirect()->route('Product.index')->with('success', 'Produk berhasil diperbarui!');
}


    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('Product.index');
    }

    public function updateStock(Request $request, $id)
{
    $request->validate([
        'stock' => 'required|integer|min:0'
    ]);

    $product = Product::findOrFail($id);
    $product->stock = $request->stock;
    $product->save();

    return redirect()->route('Product.index')->with('success', 'Stok berhasil diperbarui!');
}


}

