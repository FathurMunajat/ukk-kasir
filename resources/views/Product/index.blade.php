@extends('layouts.app')
@section('title', 'Daftar Produk')

@section('content')
    <div class="mb-4">
        <div class="breadcrumb">
            <span class="breadcrumb-item active">Produk</span>
        </div>
        <h2 class="page-title">Daftar Produk</h2>
    </div>

    <div class="mb-3 text-end">
      @if (in_array(Auth::user()->role, ['admin']))
        <a href="{{ route('Product.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
      @endif
    </div>

    <div class="card p-3">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Stok
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Gambar
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Harga
                        </th>
                        @if (Auth::user()->role === 'admin')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="px-6 py-4">
                                {{ $data->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->stock }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($item->image)
                                    <img src="{{ asset('images/' . $item->image) }}" alt="gambar" width="60"
                                        class="img-thumbnail">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                Rp{{ number_format($item->price, 2, ',', '.') }}
                            </td>
                            @if (Auth::user()->role === 'admin')
                                <td>
                                    <a href="{{ route('Product.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 transition duration-200">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('Product.destroy', $item->id) }}" method="POST"
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class=" text-red-600 hover:text-red-800 transition duration-200"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}" class="text-center">Tidak ada data
                                produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $data->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
<script>
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>