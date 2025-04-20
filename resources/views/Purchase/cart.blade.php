@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-4">
    <h4 class="fw-bold mb-4">Konfirmasi Pembayaran</h4>

    <form action="{{ route('Purchase.store') }}" method="POST" id="purchaseForm">
        @csrf

        <div class="row g-4">
            <!-- Kiri: Produk -->
            <div class="col-md-6">
                <div class="border p-3 rounded shadow-sm bg-white">
                    <h6 class="fw-semibold mb-3">Produk yang Dipilih</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($products as $index => $item)
                                <tr>
                                    <td>{{ $item['product']['name'] }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp {{ number_format($item['product']['price'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @php $total += $item['subtotal']; @endphp

                                <!-- Hidden input untuk produk -->
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $item['product']['id'] }}">
                                <input type="hidden" name="products[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between fw-semibold mt-3">
                        <span>Total Harga</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <!-- Hidden total harga -->
                    <input type="hidden" name="total_price" value="{{ $total }}">
                </div>
            </div>

            <!-- Kanan: Opsi Pembayaran -->
            <div class="col-md-6">
                <div class="border p-3 rounded shadow-sm bg-white">
                    <h6 class="fw-semibold mb-3">Opsi Pembayaran</h6>

                    <div class="mb-3">
                        <label for="status_member" class="form-label">Status Member</label>
                        <select name="status_member" id="status_member" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="non-member" selected>Bukan Member</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="phoneSection">
                        <label class="form-label">Nomor Telepon Member</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="08xxxxxxxx">
                    </div>

                    <div class="mb-3">
                        <label for="price_display" class="form-label">Total Bayar</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="price_display" class="form-control" placeholder="Masukkan total pembayaran" required>
                            <input type="hidden" id="price" name="total_payment" value="{{ old('total_payment') }}">
                        </div>
                    </div>

                    <!-- Hidden untuk total_change -->
                    <input type="hidden" name="total_change" id="total_change" value="0">

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Lanjutkan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusMember = document.getElementById('status_member');
    const phoneSection = document.getElementById('phoneSection');
    const phoneInput = document.getElementById('phone');
    const form = document.getElementById('purchaseForm');

    const priceDisplay = document.getElementById('price_display');
    const priceHidden = document.getElementById('price');
    const totalChangeInput = document.getElementById('total_change');
    const totalPrice = {{ $total }};

    // Format angka → rupiah
    const formatRupiah = (angka) => {
        return angka.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };
    const unformatRupiah = (angka) => {
        return angka.replace(/\./g, '');
    };

    // Saat ketik total bayar
    priceDisplay.addEventListener('input', function () {
        let raw = unformatRupiah(this.value);
        if (parseInt(raw) > 1000000000) raw = "1000000000";
        this.value = formatRupiah(raw);
        priceHidden.value = raw;

        // Hitung kembalian
        const bayar = parseInt(raw) || 0;
        const kembalian = bayar - totalPrice;
        totalChangeInput.value = kembalian >= 0 ? kembalian : 0;
    });

    // Ubah form action jika member
    statusMember.addEventListener('change', function () {
        if (this.value === 'member') {
            form.action = "{{ route('Purchase.confirm') }}";
            phoneSection.classList.remove('d-none');
            phoneInput.required = true;
        } else {
            form.action = "{{ route('Purchase.store') }}";
            phoneSection.classList.add('d-none');
            phoneInput.required = false;
            phoneInput.value = '';
        }
    });
});
</script>
@endsection
