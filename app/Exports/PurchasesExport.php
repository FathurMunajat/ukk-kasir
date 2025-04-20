<?php
namespace App\Exports;

use App\Models\Purchases;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Purchases::with(['customer', 'user'])->latest()->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Pelanggan', 'Tanggal', 'Total Harga', 'Kasir'];
    }

    public function map($purchase): array
    {
        return [
            $purchase->id,
            $purchase->customer->name ?? '-',
            $purchase->purchase_date ?? $purchase->created_at->format('Y-m-d'),
            $purchase->total_price,
            $purchase->user->name ?? '-',
        ];
    }
}
