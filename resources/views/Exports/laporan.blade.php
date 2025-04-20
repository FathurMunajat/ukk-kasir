<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kasir</th>
            <th>Tanggal</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchases as $i => $purchase)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $purchase->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y H:i') }}</td>
                <td>{{ $purchase->total_price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
