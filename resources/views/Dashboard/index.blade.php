@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="mb-4">
        <div class="breadcrumb">
            <span class="breadcrumb-item">Dashboard</span>
        </div>
        <h2 class="page-title">Dashboard</h2>
    </div>

      

    @if (in_array(Auth::user()->role, ['admin']))
    <div class="row g-3">
        <div class="col-md-6">
            <div class="chart-container card p-3">
                <canvas id="barChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container card p-3">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    

    
@endsection

@section('scripts')
    <script>
        // chart js
        const barChartData = {
            labels: {!! json_encode($sales['labels']) !!},
            datasets: [{
                label: 'Jumlah Penjualan',
                data: {!! json_encode($sales['data']) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const pieChartData = {
            labels: {!! json_encode($products->pluck('name')) !!},
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
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Penjualan per Tanggal'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            new Chart(document.getElementById('pieChart').getContext('2d'), {
                type: 'pie',
                data: pieChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Stok Produk'
                        }
                    }
                }
            });
        });
    </script>

@endsection
