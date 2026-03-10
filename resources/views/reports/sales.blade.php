@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan Penjualan</li>
@endsection

@section('content')
<!-- Filter & Export -->
<div class="table-card p-4 mb-4">
    <form action="{{ route('reports.sales') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
            </div>
            <div class="col-md-6 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i>Tampilkan
                </button>
                <a href="{{ route('reports.sales.pdf') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}"
                    target="_blank" class="btn btn-outline-danger">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
                <a href="{{ route('reports.sales.excel') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}"
                    class="btn btn-outline-success">
                    <i class="bi bi-file-excel me-1"></i>Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:#dbeafe">
                <i class="bi bi-receipt" style="color:#1d4ed8"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ $sales->count() }}</h3>
            <p class="text-muted small mb-0">Total Transaksi</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:#d1fae5">
                <i class="bi bi-currency-dollar" style="color:#059669"></i>
            </div>
            <h3 class="fw-bold mb-0" style="font-size:1.1rem">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="text-muted small mb-0">Total Pendapatan</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:#fef3c7">
                <i class="bi bi-box" style="color:#d97706"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ number_format($totalItems) }}</h3>
            <p class="text-muted small mb-0">Total Item Terjual</p>
        </div>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="table-card">
    <div class="p-4 border-bottom">
        <h6 class="fw-bold mb-0">
            Detail Penjualan: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} –
            {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Nota</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Kasir</th>
                    <th class="text-center">Item</th>
                    <th>Pembayaran</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $i => $sale)
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="fw-semibold text-decoration-none text-primary">
                            {{ $sale->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:.875rem">{{ $sale->sale_date->format('d M Y') }}</td>
                    <td style="font-size:.875rem">{{ $sale->customer_name ?? 'Umum' }}</td>
                    <td style="font-size:.875rem">{{ $sale->user->name }}</td>
                    <td class="text-center">{{ $sale->items->sum('quantity') }}</td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            {{ strtoupper($sale->payment_method) }}
                        </span>
                    </td>
                    <td class="text-end fw-semibold" style="color:#059669">
                        Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-bar-chart fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Tidak ada data penjualan pada periode ini</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($sales->count() > 0)
            <tfoot>
                <tr class="border-top" style="background:#f8fafc">
                    <td colspan="7" class="text-end fw-bold">TOTAL:</td>
                    <td class="text-end fw-bold text-success">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
