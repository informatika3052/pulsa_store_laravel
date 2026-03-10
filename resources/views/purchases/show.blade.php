@extends('layouts.app')

@section('title', 'Detail Pembelian')
@section('page-title', 'Detail Pembelian')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Pembelian</a></li>
    <li class="breadcrumb-item active">{{ $purchase->invoice_number }}</li>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="table-card p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-bold mb-1">{{ $purchase->invoice_number }}</h5>
                    <span class="text-muted small">{{ $purchase->purchase_date->format('d F Y') }}</span>
                </div>
                <a href="{{ route('purchases.print', $purchase) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="bi bi-printer me-1"></i>Cetak PO
                </a>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $i => $item)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $item->product->name }}</div>
                                <div class="text-muted" style="font-size:.75rem"><code>{{ $item->product->code }}</code></div>
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
                            <td class="text-end fw-semibold">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @if($purchase->discount > 0)
                        <tr>
                            <td colspan="4" class="text-end text-muted">Diskon:</td>
                            <td class="text-end text-danger">- Rp {{ number_format($purchase->discount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-6">GRAND TOTAL:</td>
                            <td class="text-end fw-bold fs-6 text-primary">Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="table-card p-4 mb-4">
            <h6 class="fw-bold mb-3 pb-2 border-bottom">Info Pembelian</h6>
            <dl class="row mb-0" style="font-size:.875rem">
                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7">
                    @if($purchase->status == 'confirmed')
                        <span class="badge bg-success">Confirmed</span>
                    @elseif($purchase->status == 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </dd>
                <dt class="col-5 text-muted">Supplier</dt>
                <dd class="col-7">{{ $purchase->supplier->name ?? '-' }}</dd>
                <dt class="col-5 text-muted">Diiinput oleh</dt>
                <dd class="col-7">{{ $purchase->user->name }}</dd>
                @if($purchase->notes)
                <dt class="col-5 text-muted">Catatan</dt>
                <dd class="col-7">{{ $purchase->notes }}</dd>
                @endif
            </dl>
        </div>
        <a href="{{ route('purchases.index') }}" class="btn btn-light w-100">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>
@endsection
