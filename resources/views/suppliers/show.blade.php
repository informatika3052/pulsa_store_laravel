@extends('layouts.app')

@section('title', 'Detail Supplier')
@section('page-title', 'Detail Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Supplier</a></li>
    <li class="breadcrumb-item active">{{ $supplier->name }}</li>
@endsection

@section('content')
<div class="row g-4">

    <!-- Info Supplier -->
    <div class="col-lg-4">
        <div class="table-card p-4 mb-4">
            <div class="text-center mb-4">
                <div class="mx-auto rounded d-flex align-items-center justify-content-center mb-3"
                    style="width:64px;height:64px;background:#ede9fe">
                    <i class="bi bi-truck fs-2" style="color:#7c3aed"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $supplier->name }}</h5>
                @if($supplier->contact_person)
                <p class="text-muted small mb-0">{{ $supplier->contact_person }}</p>
                @endif
            </div>

            <hr>

            <dl class="row mb-0" style="font-size:.875rem">
                @if($supplier->phone)
                <dt class="col-5 text-muted"><i class="bi bi-telephone me-1"></i>Telepon</dt>
                <dd class="col-7">{{ $supplier->phone }}</dd>
                @endif

                @if($supplier->email)
                <dt class="col-5 text-muted"><i class="bi bi-envelope me-1"></i>Email</dt>
                <dd class="col-7">{{ $supplier->email }}</dd>
                @endif

                @if($supplier->address)
                <dt class="col-5 text-muted"><i class="bi bi-geo-alt me-1"></i>Alamat</dt>
                <dd class="col-7">{{ $supplier->address }}</dd>
                @endif

                <dt class="col-5 text-muted">Terdaftar</dt>
                <dd class="col-7">{{ $supplier->created_at->format('d M Y') }}</dd>
            </dl>

            <hr>

            <div class="d-flex gap-2">
                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm flex-grow-1">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-light btn-sm flex-grow-1">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Statistik -->
        <div class="table-card p-4">
            <h6 class="fw-bold mb-3">Statistik</h6>
            <div class="d-flex justify-content-between align-items-center p-3 rounded mb-2"
                style="background:#eff6ff">
                <span class="small text-muted">Total Produk</span>
                <span class="fw-bold text-primary">{{ $supplier->products->count() }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 rounded"
                style="background:#f0fdf4">
                <span class="small text-muted">Total Pembelian</span>
                <span class="fw-bold text-success">{{ $supplier->purchases->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Produk & Riwayat Pembelian -->
    <div class="col-lg-8">

        <!-- Daftar Produk -->
        <div class="table-card mb-4">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-box-seam me-2 text-primary"></i>
                    Produk dari Supplier ini ({{ $supplier->products->count() }})
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th class="text-center">Stok</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->products as $product)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $product->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">
                                    <code>{{ $product->code }}</code>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $product->is_low_stock ? 'bg-danger' : 'bg-success' }} rounded-pill">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="text-end" style="font-size:.875rem">
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </td>
                            <td class="text-end fw-semibold" style="font-size:.875rem;color:#059669">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada produk dari supplier ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Pembelian -->
        <div class="table-card">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-warning"></i>
                    Riwayat Pembelian Terakhir
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Tanggal</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier->purchases as $purchase)
                        <tr>
                            <td>
                                <a href="{{ route('purchases.show', $purchase) }}"
                                    class="fw-semibold text-decoration-none text-primary">
                                    {{ $purchase->invoice_number }}
                                </a>
                            </td>
                            <td style="font-size:.875rem">
                                {{ $purchase->purchase_date->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                @if($purchase->status == 'confirmed')
                                    <span class="badge bg-success rounded-pill">Confirmed</span>
                                @else
                                    <span class="badge bg-warning rounded-pill text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold" style="color:#1d4ed8">
                                Rp {{ number_format($purchase->grand_total, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                Belum ada riwayat pembelian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
