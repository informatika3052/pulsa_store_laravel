<?php
// ==================== app/Exports/SalesReportExport.php ====================
namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private ?string $dateFrom = null,
        private ?string $dateTo = null
    ) {}

    public function collection()
    {
        $query = Sale::with(['user', 'items.product'])->where('status', 'completed');

        if ($this->dateFrom) $query->whereDate('sale_date', '>=', $this->dateFrom);
        if ($this->dateTo)   $query->whereDate('sale_date', '<=', $this->dateTo);

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No. Nota', 'Tanggal', 'Kasir', 'Pelanggan', 'Jml Item',
            'Subtotal', 'Diskon', 'Total', 'Bayar', 'Metode', 'Status'
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->sale_date->format('d/m/Y'),
            $sale->user->name,
            $sale->customer_name ?? 'Umum',
            $sale->items->sum('quantity'),
            $sale->total_amount,
            $sale->discount,
            $sale->grand_total,
            $sale->paid_amount,
            strtoupper($sale->payment_method),
            $sale->status,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4F46E5'],
            ], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
        ];
    }
}
