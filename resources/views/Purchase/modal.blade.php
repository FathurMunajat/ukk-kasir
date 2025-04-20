<div>
    <h5>Detail Penjualan #{{ $purchase->id }}</h5>
    <p><strong>Nama:</strong> {{ $purchase->customer->name ?? '-' }}</p>
    <p><strong>Kasir:</strong> {{ $purchase->user->name ?? '-' }}</p>
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</p>

    <table class="table table-sm mt-3">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->details as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->amount }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3 text-end">
        <strong>Total:</strong> Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
    </div>
</div>
