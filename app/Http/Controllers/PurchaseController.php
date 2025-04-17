<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::latest()->paginate(10);

        return view('Purchase.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('Purchase.create', compact('products'));
    }

    public function store(Request $request)
{
    $quantities = session('quantities'); // ganti jadi quantities (bukan quantity)
    $totalAmount = session('payment_amount');
    $paymentAmount = $request->payment_amount;
    
    $userId = auth()->id();

    $totalAmount = floatval(str_replace(['Rp', '.', ','], '', $totalAmount));
    $paymentAmount = floatval(str_replace(['Rp', '.', ','], '', $paymentAmount));

    $totalReturn = 0;
    if ($paymentAmount > $totalAmount) {
        $totalReturn = $paymentAmount - $totalAmount;
    }

    if ($memberStatus === 'member') {
        $member = Member::firstOrCreate(
            ['phone_number' => $memberPhone],
            ['name' => 'Member Baru']
        );

        if ($member->wasRecentlyCreated) {
            $member->points = 100;
            $member->save();
        }

        session([
            'member_id' => $member->id,
            'member_name' => $member->name,
            'phone_number' => $memberPhone,
            'total_amount' => $totalAmount,
            'payment_amount' => $paymentAmount,
            'quantities' => $quantities
        ]);

        return redirect()->route('Purchase.index');
    }
}



    public function confirm()
    {
        $quantities = session('quantities', []);
        $products = Product::whereIn('id', array_keys($quantities))->get();
        $total = 0;

        foreach ($products as $product) {
            $qty = $quantities[$product->id];
            $total += $product->price * $qty;
        }

        return view('Purchase.confirm', compact('products', 'quantities', 'total'));
    }

    public function selectProduct(Request $request)
    {
        $quantities = collect($request->input('quantities'))->filter(function ($qty) {
            return $qty > 0;
        });

        // Cek apakah ada produk yang dipilih
        if ($quantities->isEmpty()) {
            return redirect()->back()->with('error', 'Silakan pilih produk terlebih dahulu.');
        }

        $products = Product::whereIn('id', $quantities->keys())->get();

        $total = 0;
        foreach ($products as $product) {
            $qty = $quantities[$product->id] ?? 0;
            $total += $product->price * $qty;
        }

        // Simpan ke session untuk halaman konfirmasi atau pembayaran
        session([
            'quantities' => $quantities,
            'payment_amount' => $total,
        ]);

        return view('Purchase.confirm', [
            'products' => $products,
            'quantities' => $quantities,
            'total' => $total,
        ]);
    }

    


    

}
