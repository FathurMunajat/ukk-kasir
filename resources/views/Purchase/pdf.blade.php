<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $purchase->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
            font-size: 14px;
        }

        h2 {
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .totals {
            width: 100%;
            margin-top: 10px;
        }

        .totals td {
            padding: 6px 10px;
        }

        .totals .label {
            text-align: right;
            width: 80%;
        }

        .totals .value {
            text-align: right;
            width: 20%;
            font-weight: bold;
            white-space: nowrap;
        }

        .payment-info {
            margin-top: 30px;
        }

        .payment-info p {
            margin: 4px 0;
        }
    </style>
</head>
<body>

    <h2>INVOICE #{{ $purchase->id }}</h2>

    <div class="info">
        <p><strong>Tanggal:</strong> {{ $purchase->created_at->format('d/m/Y') }}</p>
        <p><strong>Nama Pelanggan:</strong> {{ $purchase->customer->phone ?? 'Non-Member' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>KETERANGAN</th>
                <th class="text-right">HARGA</th>
                <th class="text-right">JUMLAH</th>
                <th class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->details as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item->amount }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $poinDigunakan = $purchase->poin ?? 0;
        $totalSetelahPoin = $purchase->total_price - $poinDigunakan;
        $poinDidapat = floor($totalSetelahPoin * 0.01);
    @endphp

    <table class="totals">
        <tr>
            <td class="label">SUB TOTAL :</td>
            <td class="value">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">POTONGAN POIN :</td>
            <td class="value">Rp {{ number_format($poinDigunakan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">TOTAL :</td>
            <td class="value">Rp {{ number_format($totalSetelahPoin, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">TOTAL KEMBALIAN :</td>
            <td class="value">Rp {{ number_format($purchase->total_change, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">POIN DIDAPAT :</td>
            <td class="value">{{ $poinDidapat }}</td>
        </tr>
    </table>

    <div class="payment-info">
        <p><strong>PEMBAYARAN :</strong></p>
        <p>Nama : Toko Drone Asli</p>
        <p>Telp: (021) 12345678</p>
        <p>Alamat: Jl. Contoh No. 123, Jakarta</p>
        <p>Terima kasih atas pembelian Anda!</p>
    </div>

</body>
</html>
