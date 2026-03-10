{{-- resources/views/salary/slip-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        .header { background: #1e1b4b; color: white; padding: 16px 20px; margin-bottom: 16px; }
        .header h2 { font-size: 16px; margin-bottom: 2px; }
        .header p { font-size: 11px; opacity: .8; }
        .content { padding: 0 20px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 6px 0; font-size: 11px; }
        .label { color: #64748b; width: 40%; }
        .divider { border-top: 1px solid #e2e8f0; margin: 10px 0; }
        .total-row { background: #f0fdf4; padding: 10px; border-radius: 6px; margin-top: 10px; }
        .total-row .amount { font-size: 16px; font-weight: bold; color: #059669; }
        .footer { margin-top: 20px; padding: 12px 20px; border-top: 1px solid #e2e8f0; font-size: 10px; color: #64748b; }
        .row { display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SLIP GAJI KARYAWAN</h2>
        <p>PulsaStore Pro | Periode: {{ $salary->month_name }} {{ $salary->year }}</p>
    </div>

    <div class="content">
        <div class="info-box">
            <table>
                <tr>
                    <td class="label">Nama Karyawan</td>
                    <td>: <strong>{{ $salary->employee->name }}</strong></td>
                    <td class="label">Kode Karyawan</td>
                    <td>: {{ $salary->employee->employee_code }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td>: {{ $salary->employee->position }}</td>
                    <td class="label">Tipe Gaji</td>
                    <td>: {{ ucfirst($salary->employee->salary_type) }}</td>
                </tr>
                <tr>
                    <td class="label">Periode</td>
                    <td>: {{ $salary->month_name }} {{ $salary->year }}</td>
                    <td class="label">Tgl. Bayar</td>
                    <td>: {{ $salary->payment_date ? $salary->payment_date->format('d/m/Y') : '-' }}</td>
                </tr>
            </table>
        </div>

        <table>
            <tr>
                <td class="label">Gaji Pokok</td>
                <td class="text-right" style="text-align:right">Rp {{ number_format($salary->base_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label" style="color:#059669">+ Tunjangan</td>
                <td class="text-right" style="text-align:right;color:#059669">Rp {{ number_format($salary->allowance, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label" style="color:#0891b2">+ Bonus</td>
                <td class="text-right" style="text-align:right;color:#0891b2">Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label" style="color:#dc2626">- Potongan</td>
                <td class="text-right" style="text-align:right;color:#dc2626">Rp {{ number_format($salary->deduction, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="total-row row">
            <span style="font-weight:bold">TOTAL GAJI DITERIMA:</span>
            <span class="amount">Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</span>
        </div>

        @if($salary->notes)
        <div style="margin-top:12px;font-size:11px;color:#64748b">
            <strong>Catatan:</strong> {{ $salary->notes }}
        </div>
        @endif

        <div style="margin-top:24px;display:flex;justify-content:flex-end">
            <div style="text-align:center">
                <p style="font-size:11px;margin-bottom:40px">Tanda Tangan Karyawan</p>
                <p style="font-size:11px;border-top:1px solid #000;padding-top:4px">{{ $salary->employee->name }}</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak oleh sistem PulsaStore Pro | {{ now()->format('d/m/Y H:i') }}</p>
        <p>Slip gaji ini sah tanpa tanda tangan basah jika dicetak dari sistem.</p>
    </div>
</body>
</html>
