@extends('layouts.app')
@section('title', 'Edit Produk')

@section('content')
    <div class="mb-4">
        <div class="breadcrumb">
            <a href="{{ route('Product.index') }}" class="breadcrumb-item">Produk</a>
            <span class="breadcrumb-item active">Edit Produk</span>
        </div>
        <h2 class="page-title">Edit Produk</h2>
    </div>

    <div class="card p-4">
        <form action="{{ route('Product.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $item->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar Produk</label><br>
                @if ($item->image)
                    <img src="{{ asset('images/' . $item->image) }}" alt="gambar" width="80" class="mb-2">
                @endif
                <input type="file" name="image" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stok</label>
                <input type="number" class="form-control" id="stock" name="stock"
                    value="{{ old('stock', $item->stock) }}" required>
            </div>

            <div class="mb-3">
                <label for="price_display" class="form-label">Harga</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" id="price_display" class="form-control"
                        value="{{ number_format(old('price', $item->price), 0, ',', '.') }}" placeholder="Masukkan harga"
                        required>
                    <input type="hidden" id="price" name="price" value="{{ old('price', $item->price) }}">
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('Product.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const formatRupiah = (angka) => {
            return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        const unformatRupiah = (angka) => {
            return angka.replace(/\./g, '');
        };

        // Untuk harga input
        const priceDisplay = document.getElementById('price_display');
        const priceHidden = document.getElementById('price');

        priceDisplay.addEventListener('input', function() {
            let raw = unformatRupiah(this.value);
            if (parseInt(raw) > 1000000000) raw = "1000000000";
            this.value = formatRupiah(raw);
            priceHidden.value = raw;
        });
    </script>
@endsection
