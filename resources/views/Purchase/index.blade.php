@extends('layouts.app')
@section('title', 'Daftar Penjualan')

@section('content')
<div class="mb-4">
    <div class="breadcrumb">
        <span class="breadcrumb-item active">Pembelian</span>
    </div>
    <h2 class="page-title">Daftar Pembelian</h2>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    @if (Auth::user()->role === 'user')
        <a href="{{ route('Purchase.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah Penjualan
        </a>
    @endif
    <a href="{{ route('Purchase.export.excel') }}" class="btn btn-primary">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Penjualan</th>
                        <th>Total Harga</th>
                        <th>Dibuat Oleh</th>
                        @if (Auth::check() && Auth::user()->role === 'user')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $index => $purchase)
                        <tr>
                            <td class="text-center">{{ $purchases->firstItem() + $index }}</td>
                            <td>{{ $purchase->customer->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                            <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                            <td>{{ $purchase->user->name ?? '-' }}</td>

                            @if (Auth::check() && Auth::user()->role === 'user')
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-info btn-sm view-detail-btn"
                                        data-id="{{ $purchase->id }}" data-bs-toggle="modal" data-bs-target="#detailModal">
                                        <i class="bi bi-eye"></i> Lihat
                                    </button>

                                    <a href="{{ route('Purchase.download', $purchase->id) }}"
                                        class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-download"></i> Unduh Bukti
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada data penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $purchases->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div id="modal-loading" class="text-center my-3">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div id="modal-content" style="display: none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalContent = document.getElementById('modal-content');
        const modalLoading = document.getElementById('modal-loading');

        document.querySelectorAll('.view-detail-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                modalContent.style.display = 'none';
                modalLoading.style.display = 'block';

                fetch(`/Purchase/modal/${id}`)
                    .then(res => res.text())
                    .then(html => {
                        modalContent.innerHTML = html;
                        modalLoading.style.display = 'none';
                        modalContent.style.display = 'block';
                    })
                    .catch(() => {
                        modalContent.innerHTML =
                            '<div class="text-danger">Gagal memuat data.</div>';
                        modalLoading.style.display = 'none';
                        modalContent.style.display = 'block';
                    });
            });
        });
    });
</script>
@endsection
