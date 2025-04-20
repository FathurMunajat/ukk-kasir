@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="container py-4">
    <h4 class="mb-4 fw-semibold">Tambah Penjualan</h4>

    <form action="{{ route('Purchase.cart') }}" method="POST">

        @csrf

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="{{ asset('images/' . $product->image) }}" 
                             class="card-img-top" 
                             alt="{{ $product->name }}" 
                             style="height: 160px; object-fit: contain;">

                        <div class="card-body text-center">
                            <h6 class="fw-bold">{{ $product->name }}</h6>
                            <p class="small text-muted mb-1">Stok: {{ $product->stock }}</p>
                            <p class="fw-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                            @if ($product->stock > 0)
                                <div class="input-group input-group-sm justify-content-center mb-2" style="max-width: 130px; margin: 0 auto;">
                                    <button class="btn btn-outline-secondary btn-decrement" type="button">−</button>
                                    <input type="number" name="quantities[{{ $product->id }}]" 
                                           class="form-control text-center quantity-input"
                                           value="0" min="0" max="{{ $product->stock }}">
                                    <button class="btn btn-outline-secondary btn-increment" type="button">+</button>
                                </div>
                                <small class="text-muted">Sub Total: Rp <span class="subtotal" data-price="{{ $product->price }}">0</span></small>
                            @else
                                <div class="alert alert-danger p-1 mt-2 small">Stok habis</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <input type="hidden" name="total_payment" id="total-amount-input" value="0">

        <div class="fixed-bottom bg-white py-3 px-4 border-top shadow-sm" style="z-index: 1050;">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <strong>Total: Rp <span id="total-amount">0</span></strong>
                <button type="submit" class="btn btn-primary px-4" id="submitButton" disabled>
                    Lanjut <i class="fas fa-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const subtotals = document.querySelectorAll('.subtotal');
    const totalAmountDisplay = document.getElementById('total-amount');
    const totalAmountInput = document.getElementById('total-amount-input');
    const submitButton = document.getElementById('submitButton');

    function updateTotals() {
        let total = 0;
        let productSelected = false;
        quantityInputs.forEach((input, index) => {
            const qty = parseInt(input.value) || 0;
            const price = parseInt(subtotals[index].dataset.price);
            const subtotal = qty * price;
            subtotals[index].innerText = subtotal.toLocaleString('id-ID');
            total += subtotal;

            if (qty > 0) {
                productSelected = true;
            }
        });

        totalAmountDisplay.innerText = total.toLocaleString('id-ID');
        totalAmountInput.value = total;
        submitButton.disabled = !productSelected;
    }

    document.querySelectorAll('.btn-increment').forEach((btn, index) => {
        btn.addEventListener('click', function () {
            const input = quantityInputs[index];
            const max = parseInt(input.getAttribute('max'));
            let value = parseInt(input.value) || 0;
            if (value < max) {
                input.value = value + 1;
                input.dispatchEvent(new Event('input'));
            }
        });
    });

    document.querySelectorAll('.btn-decrement').forEach((btn, index) => {
        btn.addEventListener('click', function () {
            const input = quantityInputs[index];
            let value = parseInt(input.value) || 0;
            if (value > 0) {
                input.value = value - 1;
                input.dispatchEvent(new Event('input'));
            }
        });
    });

    quantityInputs.forEach((input) => {
        input.addEventListener('input', updateTotals);
    });

    updateTotals(); // call once on load
});
</script>
@endsection
