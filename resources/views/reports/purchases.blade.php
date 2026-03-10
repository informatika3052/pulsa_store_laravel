@extends('layouts.app')

@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan Pembelian</li>
@endsection

@section('content')
<div class="table-card p-4 mb-4">
    <form action="{{ route('reports.purchases') }}" method="GET">
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
                <a href="{{ route('reports.purchases.excel') }}?date_from={{ $dateFrom }}&date_to={{ $dateTo }}"
                    class="btn btn-outline-success">
                    <i class="bi bi-file-excel me-1"></i>Export Excel
                </a>
            </div>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:#fef3c7">
                <i class="bi bi-bag" style="color:#d97706"></i>
            </div>
            <h3 class="fw-bold mb-0">{{ $purchases->count() }}</h3>
            <p class="text-muted small mb-0">Total Transaksi Pembelian</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="stat-icon mb-3" style="background:#fee2e2">
                <i class="bi bi-currency-dollar" style="color:#dc2626"></i>
            </div>
            <h3 class="fw-bold mb-0" style="font-size:1.1rem">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
            <p class="text-muted small mb-0">Total Pengeluaran</p>
        </div>
    </div>
</div>

<div class="table-card">
    <div class="p-4 border-bottom">
        <h6 class="fw-bold mb-0">
            Detail Pembelian: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} –
            {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. PO</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Admin</th>
                    <th class="text-center">Produk</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $i => $purchase)
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase) }}" class="fw-semibold text-decoration-none text-primary">
                            {{ $purchase->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:.875rem">{{ $purchase->purchase_date->format('d M Y') }}</td>
                    <td style="font-size:.875rem">{{ $purchase->supplier->name ?? '-' }}</td>
                    <td style="font-size:.875rem">{{ $purchase->user->name }}</td>
                    <td class="text-center">{{ $purchase->items->count() }} produk</td>
                    <td class="text-end fw-semibold" style="color:#1d4ed8">
                        Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-bag-x fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Tidak ada data pembelian pada periode ini</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($purchases->count() > 0)
            <tfoot>
                <tr class="border-top" style="background:#f8fafc">
                    <td colspan="6" class="text-end fw-bold">TOTAL:</td>
                    <td class="text-end fw-bold text-primary">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
