@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
    <div class="mb-4">
        <div class="breadcrumb">
            <a href="{{ route('Product.index') }}" class="breadcrumb-item">Produk</a>
            <span class="breadcrumb-item active">Tambah Produk</span>
        </div>
        <h2 class="page-title">Tambah Produk</h2>
    </div>

    <div class="card p-4">
        <form action="{{ route('Product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Produk</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Gambar</label>
                <input type="file" name="image" class="form-control" required onchange="previewImage(event)">
                <div class="mt-2">
                    <img id="imagePreview" src="#" alt="Preview Gambar" style="display:none; max-width: 200px;">
                </div>
            </div>

            <div class="mb-3">
                <label for="price_display" class="form-label">Harga</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" id="price_display" class="form-control @error('price') is-invalid @enderror"
                        placeholder="Masukkan harga" required>
                    <input type="hidden" id="price" name="price" value="{{ old('price') }}">
                </div>

                @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            @error('price')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="{{ route('Product.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
    </div>
@endsection

@section('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById('imagePreview');
                img.src = reader.result;
                img.style.display = 'block';
            };
            if (input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }

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
