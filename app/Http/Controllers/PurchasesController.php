<?php

namespace App\Http\Controllers;

use App\Models\Purchases;
use App\Models\Customers;
use App\Models\DetailPurchases;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchasesExport;


class PurchasesController extends Controller
{
    public function index()
    {
        $purchases = Purchases::with(['customer', 'details.product', 'user'])->latest()->paginate(10);
        return view('Purchase.index', compact('purchases'));
    }

    public function create()
    {
        $customers = Customers::all();
        $products = Product::all();
        return view('Purchase.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_price' => 'required|integer',
            'total_payment' => 'required|integer',
            'total_change' => 'required|integer',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $products = $request->input('products');
        $total_price = $request->input('total_price');
        $total_payment = $request->input('total_payment');
        $total_change = $request->input('total_change');

        DB::beginTransaction();

        try {
            $customerId = null;
            $poin = 0;
            $totalPoin = 0;

            if ($request->has('status_member') && $request->input('status_member') === 'member') {
                $phone = $request->input('phone');
                $name = $request->input('name');
                $usePoint = $request->has('use_point');

                $customer = Customers::where('phone', $phone)->first();
                if (!$customer) {
                    $customer = Customers::create([
                        'name' => $name,
                        'phone' => $phone,
                        'poin' => 0
                    ]);
                }

                $customerId = $customer->id;

                $poin = floor($total_price / 100); // hitung poin berdasarkan total harga
                $customer->poin += $poin;


                $poinUsed = 0;

                if ($usePoint && $customer->poin > 0) {
                    $poinUsed = $customer->poin;
                    $total_price -= $poinUsed;
                    $customer->poin = 0;
                    $total_change = $total_payment - $total_price;
                }



                $customer->save();
                $totalPoin = $customer->poin;
            }

            $purchase = Purchases::create([
                'customer_id' => $customerId,
                'user_id' => auth()->id(),
                'purchases_date' => now(),
                'total_price' => $total_price,
                'total_payment' => $total_payment,
                'total_change' => $total_change,
                'poin' => $poin,
                'total_poin' => $totalPoin,
                'poin_used' => $poinUsed ?? 0,
            ]);

            foreach ($products as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk '{$product->name_product}' tidak mencukupi.");
                }

                $qty = $item['quantity'];
                $subtotal = $qty * $product->price;

                DetailPurchases::create([
                    'purchases_id' => $purchase->id,
                    'product_id' => $product->id,
                    'amount' => $qty,
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->stock -= $qty;
                $product->save();
            }

            DB::commit();
            return redirect()->route('Purchase.invoice', $purchase->id)->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $purchase = Purchases::findOrFail($id);
        $customers = Customers::all();
        $products = Product::all();
        return view('Purchase.edit', compact('purchase', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'purchases_date' => 'nullable|date',
            'total_price' => 'nullable|integer',
            'total_payment' => 'nullable|integer',
            'total_change' => 'nullable|integer',
            'poin' => 'nullable|integer',
            'total_poin' => 'nullable|integer',
        ]);

        $purchase = Purchases::findOrFail($id);
        $purchase->update($validated);

        return redirect()->route('Purchase.index')->with('success', 'Data penjualan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $purchase = Purchases::findOrFail($id);
        $purchase->delete();

        return redirect()->route('Purchase.index')->with('success', 'Data penjualan berhasil dihapus');
    }

    public function cart(Request $request)
    {
        $quantities = $request->input('quantities', []);
        $selectedProducts = [];

        foreach ($quantities as $productId => $qty) {
            if ($qty > 0) {
                $product = Product::find($productId);
                if ($product) {
                    $selectedProducts[] = [
                        'product' => $product,
                        'quantity' => $qty,
                        'subtotal' => $qty * $product->price,
                    ];
                }
            }
        }

        if (empty($selectedProducts)) {
            return redirect()->back()->with('error', 'Pilih minimal satu produk.');
        }

        $total_price = array_sum(array_column($selectedProducts, 'subtotal'));
        $total_payment = $request->input('total_payment', $total_price);

        return view('Purchase.cart', [
            'products' => $selectedProducts,
            'total_price' => $total_price,
            'total_payment' => $total_payment,
            'member' => null
        ]);
    }

    public function confirm(Request $request)
    {
        $status = $request->input('status_member');
        $rawProducts = $request->input('products');
        $total_price = (int) $request->input('total_price');
        $total_payment = (int) $request->input('total_payment');

        $products = [];
        foreach ($rawProducts as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['quantity'] * $product->price,
                ];
            }
        }

        $member = Customers::where('phone', $request->phone)->first();
        $isNewMember = !$member;
        $potongan_poin = 0;

        if ($member) {
            $isFirstPurchase = Purchases::where('customer_id', $member->id)->count() === 0;
            if (!$isFirstPurchase && $member->poin >= 0.01) {
                $potongan_poin = ($member && $member->poin > 0) ? $member->poin : 0;
            }
        }

        $total_after_poin = $total_price - $potongan_poin;
        $change = $total_payment - $total_after_poin;

        return view('Purchase.confirm', [
            'products' => $products,
            'total_price' => $total_price,
            'total_payment' => $total_payment,
            'status' => $status,
            'member' => $member,
            'isNewMember' => $isNewMember,
            'potongan_poin' => $potongan_poin,
            'change' => $change,
            'phone' => $request->phone
        ]);
    }

    public function invoice($id)
    {
        $purchase = Purchases::with(['details.product', 'customer', 'user'])->findOrFail($id);
        return view('Purchase.invoice', compact('purchase'));
    }

    public function download($id)
    {
        $purchase = Purchases::with(['details.product', 'customer', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('Purchase.pdf', compact('purchase'));
        return $pdf->download('invoice-' . $purchase->id . '.pdf');
    }


    public function modal($id)
    {
        $purchase = Purchases::with(['details.product', 'customer', 'user'])->findOrFail($id);
        return view('Purchase.modal', compact('purchase'));
    }

    public function exportExcel()
{
    return Excel::download(new PurchasesExport, 'daftar-penjualan.xlsx');
}
    
}
