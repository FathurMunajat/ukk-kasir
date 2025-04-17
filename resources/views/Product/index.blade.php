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
  <a href="{{ route('Product.create') }}" class="btn btn-success">
    <i class="bi bi-plus-circle"></i> Tambah Produk
  </a>
</div>


<div class="card p-3">
  <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Stok</th>
          <th>Gambar</th>
          <th>Harga</th>
          @if (Auth::user()->role === 'admin')
            <th>Aksi</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @forelse ($data as $index => $item)
        <tr>
          <td>{{ $data->firstItem() + $index }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->stock }}</td>
          <td>
            @if ($item->image)
              <img src="{{ asset('images/' . $item->image) }}" alt="gambar" width="60">
            @else
              <span class="text-muted">Tidak ada</span>
            @endif
          </td>
          <td>Rp{{ number_format($item->price, 2, ',', '.') }}</td>
          @if (Auth::user()->role === 'admin')
          <td>
            <a href="{{ route('Product.edit', $item->id) }}" class="btn btn-warning btn-sm">
              <i class="bi bi-pencil-square"></i> Edit
            </a>

            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editStockModal{{ $item->id }}">
              <i class="bi bi-box-seam"></i> Edit Stok
            </button>

            <form action="{{ route('Product.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i> Hapus
              </button>
            </form>
          </td>
          @endif
        </tr>

        {{-- Modal hanya untuk admin --}}
        @if (Auth::user()->role === 'admin')
        <div class="modal fade" id="editStockModal{{ $item->id }}" tabindex="-1" aria-labelledby="editStockLabel{{ $item->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <form action="{{ route('Product.updateStock', $item->id) }}" method="POST">
              @csrf
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editStockLabel{{ $item->id }}">Edit Stok - {{ $item->name }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label for="stock" class="form-label">Jumlah Stok Baru</label>
                    <input type="number" class="form-control" name="stock" value="{{ $item->stock }}" min="0" required>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        @endif
        @empty
        <tr>
          <td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}" class="text-center">Tidak ada data produk.</td>
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
