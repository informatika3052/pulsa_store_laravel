<?php


// ==================== app/Exports/PurchaseReportExport.php ====================
namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseReportExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null
    ) {}

    public function collection()
    {
        $query = Purchase::with(['supplier', 'user', 'items'])->where('status', 'confirmed');
        if ($this->dateFrom) $query->whereDate('purchase_date', '>=', $this->dateFrom);
        if ($this->dateTo)   $query->whereDate('purchase_date', '<=', $this->dateTo);
        return $query->latest()->get();
    }

    public function headings(): array
    {
        return ['No. PO', 'Tanggal', 'Supplier', 'Admin', 'Total', 'Diskon', 'Grand Total', 'Status'];
    }

    public function map($purchase): array
    {
        return [
            $purchase->invoice_number,
            $purchase->purchase_date->format('d/m/Y'),
            $purchase->supplier->name ?? '-',
            $purchase->user->name,
            $purchase->total_amount,
            $purchase->discount,
            $purchase->grand_total,
            $purchase->status,
        ];
    }
}
