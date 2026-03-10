@extends('layouts.app')

@section('title', 'Daftar Penjualan')
@section('page-title', 'Transaksi Penjualan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Penjualan</li>
@endsection

@section('content')
<div class="table-card">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Daftar Transaksi Penjualan</h5>
                <p class="text-muted small mb-0">Total {{ $sales->total() }} transaksi</p>
            </div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Transaksi Baru
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('sales.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="No. nota / pelanggan...">
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
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Nota</th>
                    <th>Pelanggan</th>
                    <th>Kasir</th>
                    <th>Tanggal</th>
                    <th>Pembayaran</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $index => $sale)
                <tr>
                    <td class="text-muted small">{{ $sales->firstItem() + $index }}</td>
                    <td>
                        <a href="{{ route('sales.show', $sale) }}" class="fw-semibold text-decoration-none text-primary">
                            {{ $sale->invoice_number }}
                        </a>
                    </td>
                    <td style="font-size:.875rem">{{ $sale->customer_name ?? 'Umum' }}</td>
                    <td style="font-size:.875rem">{{ $sale->user->name }}</td>
                    <td style="font-size:.875rem">{{ $sale->sale_date->format('d M Y') }}</td>
                    <td>
                        @php $pm = $sale->payment_method; @endphp
                        <span class="badge bg-light text-dark border">
                            {{ $pm == 'cash' ? '💵 Cash' : ($pm == 'transfer' ? '🏦 Transfer' : '📱 QRIS') }}
                        </span>
                    </td>
                    <td class="text-end fw-semibold" style="color:#059669;font-size:.875rem">
                        Rp {{ number_format($sale->grand_total, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($sale->status == 'completed')
                            <span class="badge bg-success rounded-pill">Selesai</span>
                        @elseif($sale->status == 'pending')
                            <span class="badge bg-warning rounded-pill">Pending</span>
                        @else
                            <span class="badge bg-danger rounded-pill">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-light" title="Detail">
                                <i class="bi bi-eye text-info"></i>
                            </a>
                            <a href="{{ route('sales.print', $sale) }}" target="_blank" class="btn btn-sm btn-light" title="Cetak Nota">
                                <i class="bi bi-printer text-primary"></i>
                            </a>
                            @if($sale->status != 'cancelled')
                            <button type="button" class="btn btn-sm btn-light"
                                onclick="confirmDelete('{{ route('sales.destroy', $sale) }}', '{{ $sale->invoice_number }}')"
                                title="Batalkan">
                                <i class="bi bi-x-circle text-danger"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Belum ada transaksi penjualan</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sales->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $sales->firstItem() }}–{{ $sales->lastItem() }} dari {{ $sales->total() }} data
        </div>
        {{ $sales->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Konfirmasi Batalkan -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">⚠️</div>
                <h5 class="fw-bold mb-2">Batalkan Transaksi?</h5>
                <p class="text-muted mb-4">Transaksi <strong id="deleteItemName"></strong> akan dibatalkan dan stok akan dikembalikan.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Tidak</button>
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Batalkan!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, name) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteItemName').textContent = name;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
