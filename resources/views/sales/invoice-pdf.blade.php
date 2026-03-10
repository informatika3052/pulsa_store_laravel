{{-- resources/views/sales/invoice-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 11px; color: #000; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; }
        .header { text-align: center; margin-bottom: 8px; }
        .header h2 { font-size: 14px; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 2px 0; vertical-align: top; }
        .right { text-align: right; }
        .footer { margin-top: 10px; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>PULSA STORE PRO</h2>
        <div>Jl. Contoh No. 1, Kota Anda</div>
        <div>Telp: 08xx-xxxx-xxxx</div>
    </div>

    <div class="divider"></div>

    <div>
        <div class="row"><span>No. Nota:</span><span class="bold">{{ $sale->invoice_number }}</span></div>
        <div class="row"><span>Tanggal:</span><span>{{ $sale->sale_date->format('d/m/Y H:i') }}</span></div>
        <div class="row"><span>Kasir:</span><span>{{ $sale->user->name }}</span></div>
        @if($sale->customer_name)
        <div class="row"><span>Pelanggan:</span><span>{{ $sale->customer_name }}</span></div>
        @endif
    </div>

    <div class="divider"></div>

    <table>
        @foreach($sale->items as $item)
        <tr>
            <td colspan="2">{{ $item->product->name }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
            <td class="right">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <div class="row"><span>Subtotal:</span><span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span></div>
    @if($sale->discount > 0)
    <div class="row"><span>Diskon:</span><span>- Rp {{ number_format($sale->discount, 0, ',', '.') }}</span></div>
    @endif
    <div class="row bold"><span>TOTAL:</span><span>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</span></div>
    <div class="divider"></div>
    <div class="row"><span>Bayar ({{ strtoupper($sale->payment_method) }}):</span><span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span></div>
    <div class="row"><span>Kembalian:</span><span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span></div>

    <div class="divider"></div>
    <div class="footer">
        <div>Terima kasih telah berbelanja!</div>
        <div>Barang yang sudah dibeli tidak dapat dikembalikan</div>
    </div>
</body>
</html>
