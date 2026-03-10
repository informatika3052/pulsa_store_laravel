@extends('layouts.app')

@section('title', 'Detail Penjualan')
@section('page-title', 'Detail Transaksi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Penjualan</a></li>
    <li class="breadcrumb-item active">{{ $sale->invoice_number }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Info Nota -->
        <div class="table-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-bold mb-1">{{ $sale->invoice_number }}</h5>
                    <span class="text-muted small">{{ $sale->sale_date->format('d F Y') }}</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('sales.print', $sale) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-printer me-1"></i>Cetak Nota
                    </a>
                    @if($sale->status != 'cancelled')
                    <button onclick="confirmDelete('{{ route('sales.destroy', $sale) }}')"
                        class="btn btn-outline-danger">
                        <i class="bi bi-x-circle me-1"></i>Batalkan
                    </button>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $i => $item)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $item->product->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $item->product->category->name }}</div>
                            </td>
                            <td class="text-center">{{ $item->quantity }} {{ $item->product->unit }}</td>
                            <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-top">
                            <td colspan="4" class="text-end text-muted">Subtotal:</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @if($sale->discount > 0)
                        <tr>
                            <td colspan="4" class="text-end text-muted">Diskon:</td>
                            <td class="text-end text-danger">- Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-6">TOTAL:</td>
                            <td class="text-end fw-bold fs-6 text-success">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3 pb-2 border-bottom">Info Transaksi</h6>
            <dl class="row mb-0" style="font-size:.875rem">
                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7">
                    @if($sale->status == 'completed')
                        <span class="badge bg-success">Selesai</span>
                    @elseif($sale->status == 'cancelled')
                        <span class="badge bg-danger">Dibatalkan</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </dd>
                <dt class="col-5 text-muted">Kasir</dt>
                <dd class="col-7">{{ $sale->user->name }}</dd>
                <dt class="col-5 text-muted">Pelanggan</dt>
                <dd class="col-7">{{ $sale->customer_name ?? 'Umum' }}</dd>
                @if($sale->customer_phone)
                <dt class="col-5 text-muted">No. HP</dt>
                <dd class="col-7">{{ $sale->customer_phone }}</dd>
                @endif
                <dt class="col-5 text-muted">Pembayaran</dt>
                <dd class="col-7">
                    {{ $sale->payment_method == 'cash' ? '💵 Cash' : ($sale->payment_method == 'transfer' ? '🏦 Transfer' : '📱 QRIS') }}
                </dd>
                <dt class="col-5 text-muted">Dibayar</dt>
                <dd class="col-7">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</dd>
                <dt class="col-5 text-muted">Kembalian</dt>
                <dd class="col-7">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</dd>
                @if($sale->notes)
                <dt class="col-5 text-muted">Catatan</dt>
                <dd class="col-7">{{ $sale->notes }}</dd>
                @endif
            </dl>
        </div>

        <a href="{{ route('sales.index') }}" class="btn btn-light w-100">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">⚠️</div>
                <h5 class="fw-bold mb-2">Batalkan Transaksi?</h5>
                <p class="text-muted mb-4">Stok produk akan dikembalikan otomatis.</p>
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
function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
