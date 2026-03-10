{{-- resources/views/purchases/invoice-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #1e1b4b; }
        .brand h2 { font-size: 18px; color: #1e1b4b; }
        .brand p { font-size: 11px; color: #64748b; margin-top: 4px; }
        .po-info { text-align: right; }
        .po-info h3 { font-size: 14px; color: #1e1b4b; }
        .po-info p { font-size: 11px; color: #64748b; }
        .info-grid { display: flex; gap: 20px; margin-bottom: 16px; }
        .info-box { flex: 1; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0; }
        .info-box h6 { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
        .info-box p { font-size: 11px; margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #1e1b4b; color: white; padding: 8px 10px; font-size: 11px; text-align: left; }
        td { padding: 7px 10px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .text-right { text-align: right; }
        .total-section { margin-left: auto; width: 280px; }
        .total-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 12px; }
        .grand-total { background: #1e1b4b; color: white; padding: 8px 12px; border-radius: 6px; margin-top: 8px; display: flex; justify-content: space-between; font-weight: bold; }
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">
            <h2>PULSA STORE PRO</h2>
            <p>Jl. Contoh No. 1, Kota Anda</p>
            <p>Telp: 08xx-xxxx-xxxx</p>
        </div>
        <div class="po-info">
            <h3>PURCHASE ORDER</h3>
            <p>{{ $purchase->invoice_number }}</p>
            <p>{{ $purchase->purchase_date->format('d F Y') }}</p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h6>Supplier</h6>
            <p><strong>{{ $purchase->supplier->name ?? 'Tanpa Supplier' }}</strong></p>
            @if($purchase->supplier)
            <p>{{ $purchase->supplier->phone }}</p>
            <p>{{ $purchase->supplier->address }}</p>
            @endif
        </div>
        <div class="info-box">
            <h6>Diinput Oleh</h6>
            <p><strong>{{ $purchase->user->name }}</strong></p>
            <p>{{ $purchase->purchase_date->format('d/m/Y') }}</p>
            <p>Status: <strong>{{ ucfirst($purchase->status) }}</strong></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th>Nama Produk</th>
                <th width="60" style="text-align:center">Qty</th>
                <th width="100" style="text-align:right">Harga Beli</th>
                <th width="110" style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    {{ $item->product->name }}<br>
                    <span style="color:#94a3b8;font-size:10px">{{ $item->product->code }}</span>
                </td>
                <td style="text-align:center">{{ $item->quantity }} {{ $item->product->unit }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</span>
        </div>
        @if($purchase->discount > 0)
        <div class="total-row" style="color:#dc2626">
            <span>Diskon:</span>
            <span>- Rp {{ number_format($purchase->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="grand-total">
            <span>GRAND TOTAL</span>
            <span>Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($purchase->notes)
    <div style="margin-top:16px;font-size:11px">
        <strong>Catatan:</strong> {{ $purchase->notes }}
    </div>
    @endif

    <div class="footer">
        Dokumen ini dicetak secara otomatis oleh sistem PulsaStore Pro pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
