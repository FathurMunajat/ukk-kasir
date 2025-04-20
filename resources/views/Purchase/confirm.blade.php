@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Konfirmasi Pembayaran</h4>

    <form action="{{ route('Purchase.store') }}" method="POST">
        @csrf
        <input type="hidden" name="status_member" value="{{ $status }}">

        <div class="row g-4">
            <!-- Kiri: Rincian Produk -->
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($products as $item)
                            <tr>
                                <td>{{ $item['product']['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Rp {{ number_format($item['product']['price'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @php $total += $item['subtotal']; @endphp
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between fw-semibold mt-3">
                    <span>Total Harga</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between fw-semibold mt-1">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($total_payment, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Kanan: Data Pembayaran -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" class="form-control" name="phone" value="{{ $phone }}" readonly>
                </div>

                @if ($isNewMember)
                    <div class="mb-3">
                        <label class="form-label">Nama Member Baru (Wajib Diisi)</label>
                        <input type="text" class="form-control" name="name" placeholder="Masukkan nama member baru" required>
                    </div>
                @elseif ($member)
                    <div class="mb-3">
                        <label class="form-label">Nama Member</label>
                        <input type="text" class="form-control" name="name" value="{{ $member->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="text" class="form-control" value="{{ $member->poin }}" readonly>
                    </div>

                    <div class="form-check mb-3 mt-2">
                        <input class="form-check-input" type="checkbox" name="use_point" id="use_point">
                        <label class="form-check-label" for="use_point">
                            Gunakan poin
                        </label>
                    </div>
                @endif

                <!-- Hidden data penjualan -->
                <input type="hidden" name="total_price" value="{{ $total }}">
                <input type="hidden" name="total_payment" value="{{ $total_payment }}">
                <input type="hidden" name="total_change" value="{{ $total_payment - $total }}">

                <!-- Hidden input array untuk semua produk -->
                @foreach ($products as $index => $item)
                    <input type="hidden" name="products[{{ $index }}][id]" value="{{ $item['product']['id'] }}">
                    <input type="hidden" name="products[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                @endforeach

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Selesaikan Pembayaran</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
