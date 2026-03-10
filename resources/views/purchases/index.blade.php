@extends('layouts.app')

@section('title', 'Daftar Pembelian')
@section('page-title', 'Pembelian / Barang Masuk')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pembelian</li>
@endsection

@section('content')
<div class="table-card">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Daftar Pembelian</h5>
                <p class="text-muted small mb-0">Total {{ $purchases->total() }} transaksi pembelian</p>
            </div>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Pembelian Baru
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('purchases.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Cari No. PO</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="No. invoice pembelian...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. PO</th>
                    <th>Supplier</th>
                    <th>Admin</th>
                    <th>Tanggal</th>
                    <th class="text-center">Jml Item</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $index => $purchase)
                <tr>
                    <td class="text-muted small">{{ $purchases->firstItem() + $index }}</td>
                    <td>
                        <a href="{{ route('purchases.show', $purchase) }}" class="fw-semibold text-decoration-none text-primary">
                            {{ $purchase->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:.875rem">{{ $purchase->supplier->name ?? '-' }}</td>
                    <td style="font-size:.875rem">{{ $purchase->user->name }}</td>
                    <td style="font-size:.875rem">{{ $purchase->purchase_date->format('d M Y') }}</td>
                    <td class="text-center">
                        <span class="badge bg-info-subtle text-info">{{ $purchase->items->count() }} produk</span>
                    </td>
                    <td class="text-end fw-semibold" style="color:#1d4ed8;font-size:.875rem">
                        Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($purchase->status == 'confirmed')
                            <span class="badge bg-success rounded-pill">Confirmed</span>
                        @elseif($purchase->status == 'pending')
                            <span class="badge bg-warning rounded-pill">Pending</span>
                        @else
                            <span class="badge bg-danger rounded-pill">Cancelled</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-light" title="Detail">
                                <i class="bi bi-eye text-info"></i>
                            </a>
                            <a href="{{ route('purchases.print', $purchase) }}" target="_blank" class="btn btn-sm btn-light" title="Cetak PO">
                                <i class="bi bi-printer text-primary"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-bag-x fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Belum ada data pembelian</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($purchases->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $purchases->firstItem() }}–{{ $purchases->lastItem() }} dari {{ $purchases->total() }} data
        </div>
        {{ $purchases->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection