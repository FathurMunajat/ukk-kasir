@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <div class="breadcrumb">
            <span class="breadcrumb-item">Dashboard</span>
        </div>
        <h2 class="page-title">Dashboard</h2>
    </div>

    @if (Auth::user()->role === 'admin')
        {{-- ADMIN VIEW: Chart --}}
        <div class="row g-3">
            <div class="col-md-8">
                <div class="chart-container card p-3">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="chart-container card p-3">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>

    @elseif (Auth::user()->role === 'user')
        {{-- USER VIEW: Ringkasan Penjualan --}}
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="mb-4">Selamat Datang, Petugas!</h4>

            <div class="rounded border border-gray-200 overflow-hidden">
                <div class="bg-gray-100 px-4 py-2 font-semibold">
                    Total Penjualan Hari Ini
                </div>

                <div class="text-center py-6">
                    <h1 class="text-3xl font-bold">{{ $todaySales }}</h1>
                    <p class="text-gray-600 mt-2">Jumlah total penjualan yang terjadi hari ini.</p>
                </div>

                <div class="bg-gray-100 text-sm px-4 py-2 text-right text-gray-500">
                    Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                </div>
            </div>
        </div>
       
       
        {{-- FORM FILTER --}}
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex flex-wrap gap-3 items-center">
            <div>
                <label class="text-sm text-gray-700 font-medium">
                    Filter:
                    <select name="filter" id="filter" class="ml-2 border border-gray-300 rounded px-3 py-1 text-sm">
                        <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                        <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        <option value="previous_month" {{ $filter == 'previous_month' ? 'selected' : '' }}>Bulan Lalu</option>
                    </select>
                </label>
            </div>

            <div id="date-picker" class="{{ $filter == 'daily' ? '' : 'hidden' }}">
                <label class="text-sm text-gray-700 font-medium">
                    Pilih Tanggal:
                    <input type="date" name="date" value="{{ $date ?? now()->toDateString() }}" class="border border-gray-300 rounded px-3 py-1 text-sm">
                </label>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 text-sm">
                Tampilkan
            </button>
        </form>
        <div class="mb-4">
          <a href="{{ route('dashboard.export', request()->all()) }}"
             class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700 text-sm">
             Export Excel
          </a>
      </div>
        {{-- TABEL LAPORAN --}}
        <div class="card p-4">
            @if($penjualans->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Kasir</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualans as $i => $jual)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $jual->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jual->created_at)->format('d M Y H:i') }}</td>
                                    <td>Rp {{ number_format($jual->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Tidak ada data untuk filter ini.</p>
            @endif
        </div>
    @endif
@endsection

@section('scripts')
    {{-- ADMIN SCRIPT CHART --}}
    @if (Auth::user()->role === 'admin')
        <script>
            const barChartData = {
                labels: {!! json_encode($purchases['labels']) !!},
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: {!! json_encode($purchases['purchases_date']) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            const pieChartData = {
                datasets: [{
                    label: 'Stok Produk',
                    data: {!! json_encode($products->pluck('stock')) !!},
                    backgroundColor: [
                        '#3AB0FF', '#FABB51', '#FF6666', '#33cc33', '#FF6384',
                        '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                    hoverOffset: 4
                }]
            };

            window.addEventListener('load', () => {
                new Chart(document.getElementById('barChart').getContext('2d'), {
                    type: 'bar',
                    data: barChartData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true, position: 'top' },
                            title: { display: true, text: 'Jumlah Penjualan per Tanggal' }
                        },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                new Chart(document.getElementById('pieChart').getContext('2d'), {
                    type: 'pie',
                    data: pieChartData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: true, text: 'Stok Produk' }
                        }
                    }
                });
            });
        </script>
    @endif

    {{-- SCRIPT TOGGLE TANGGAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterSelect = document.getElementById('filter');
            const datePicker = document.getElementById('date-picker');

            function toggleDatePicker() {
                const selected = filterSelect.value;
                if (selected === 'daily') {
                    datePicker.classList.remove('hidden');
                } else {
                    datePicker.classList.add('hidden');
                }
            }

            if (filterSelect && datePicker) {
                filterSelect.addEventListener('change', toggleDatePicker);
                toggleDatePicker(); // jalanin di awal
            }
        });
    </script>
@endsection
