@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Pembayaran</h4>

    <div class="bg-white p-4 rounded-4 border shadow-sm">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <strong>{{ $purchase->customer->phone ?? '-' }}</strong><br>
                MEMBER SEJAK: 
                {{ $purchase->customer ? $purchase->customer->created_at->format('d F Y') : '-' }}<br>
                MEMBER POINT: {{ $purchase->customer->poin ?? '0' }}
            </div>
            <div class="text-end">
                Invoice - #{{ $purchase->id }}<br>
                {{ $purchase->created_at->format('d F Y') }}
            </div>
        </div>

        <hr>

        <table class="table table-borderless small">
            <thead class="border-bottom">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th class="text-end">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->details as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->amount }}</td>
                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="bg-light rounded-3 p-3 mt-3 d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column small">
                <div>POIN DIGUNAKAN: <strong>{{ $purchase->poin_used ?? 0 }}</strong></div>
                <div>KASIR: <strong>{{ $purchase->user->name ?? 'Petugas' }}</strong></div>
                <div>KEMBALIAN: <strong>Rp {{ number_format($purchase->total_change, 0, ',', '.') }}</strong></div>
            </div>
            <div class="text-end">
                <div class="bg-dark text-white px-4 py-2 rounded-3 fs-5">
                    TOTAL<br>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('Purchase.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('Purchase.download', $purchase->id) }}" class="btn btn-primary">Unduh</a>
        </div>
    </div>
</div>
@endsection
