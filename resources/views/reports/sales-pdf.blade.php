{{-- resources/views/reports/sales-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { background: #1e1b4b; color: white; padding: 16px 20px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
        .header h2 { font-size: 16px; }
        .header p { font-size: 11px; opacity: .8; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f1f5f9; padding: 8px; font-size: 10px; text-transform: uppercase; letter-spacing: .05em; color: #64748b; border-bottom: 2px solid #e2e8f0; }
        td { padding: 7px 8px; border-bottom: 1px solid #f1f5f9; font-size: 11px; }
        tfoot td { background: #f8fafc; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 16px; font-size: 10px; color: #94a3b8; text-align: center; }
        .summary { display: flex; gap: 12px; margin-bottom: 16px; }
        .summary-box { flex: 1; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; text-align: center; }
        .summary-box h3 { font-size: 16px; color: #1e1b4b; }
        .summary-box p { font-size: 10px; color: #64748b; margin-top: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2>LAPORAN PENJUALAN</h2>
            <p>PulsaStore Pro</p>
        </div>
        <div style="text-align:right">
            <p>Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} – {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
            <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="summary">
        <div class="summary-box">
            <h3>{{ $sales->count() }}</h3>
            <p>Total Transaksi</p>
        </div>
        <div class="summary-box">
            <h3>Rp {{ number_format($sales->sum('grand_total'), 0, ',', '.') }}</h3>
            <p>Total Pendapatan</p>
        </div>
        <div class="summary-box">
            <h3>{{ number_format($sales->sum(fn($s) => $s->items->sum('quantity'))) }}</h3>
            <p>Total Item Terjual</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Nota</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th class="text-center">Item</th>
                <th>Metode</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $i => $sale)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->sale_date->format('d/m/Y') }}</td>
                <td>{{ $sale->customer_name ?? 'Umum' }}</td>
                <td>{{ $sale->user->name }}</td>
                <td class="text-center">{{ $sale->items->sum('quantity') }}</td>
                <td>{{ strtoupper($sale->payment_method) }}</td>
                <td class="text-right">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($sales->sum('grand_total'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem PulsaStore Pro
    </div>
</body>
</html>
