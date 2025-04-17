@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="container mx-auto p-4">
    <div class="breadcrumb mb-4">
        <a href="{{ route('Product.index') }}" class="breadcrumb-item text-grey-800 hover:underline">Produk -></a>
        <span class="breadcrumb-item active text-gray-500">Tambah Produk</span>
    </div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Tambah Produk</h2>

    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('Product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Produk -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-gray-700">Nama Produk</label>
                <input type="text" class="form-control shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" id="name" name="name" placeholder="Nama Product" required>
            </div>

            <!-- Gambar -->
            <div class="mb-4">
                <label for="image" class="block text-sm font-semibold text-gray-700">Gambar</label>
                <input type="file" name="image" class="form-control border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required onchange="previewImage(event)">
                <div class="mt-2">
                    <img id="imagePreview" src="#" alt="Preview Gambar" style="display:none; max-width: 200px; border: 1px solid #ddd; border-radius: 5px; padding: 5px;">
                </div>
            </div>

            <!-- Stok -->
            <div class="mb-4">
                <label for="stock" class="block text-sm font-semibold text-gray-700">Stok</label>
                <input type="number" class="form-control shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" id="stock" name="stock" placeholder="Stock" required min="0">
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label for="price_display" class="block text-sm font-semibold text-gray-700">Harga</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">Rp</span>
                    <input type="text" id="price_display" class="form-control pl-10 shadow-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('price') is-invalid @enderror" placeholder="Masukkan harga" required>
                    <input type="hidden" id="price" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="flex justify-between">
                <a href="{{ route('Product.index') }}" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">Kembali</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">Simpan</button>
            </div>
        </form>
    </div>
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