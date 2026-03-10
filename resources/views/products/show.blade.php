@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Barang</a></li>
    <li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('content')
<div class="row g-4">

    <!-- Kiri: Info Produk -->
    <div class="col-lg-4">
        <div class="table-card p-4 mb-4">

            <!-- Gambar Produk -->
            <div class="text-center mb-4">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                    class="rounded mb-3"
                    style="width:100%;max-height:200px;object-fit:cover">
                @else
                <div class="rounded d-flex align-items-center justify-content-center mb-3"
                    style="height:160px;background:#f1f5f9">
                    <i class="bi bi-box-seam" style="font-size:4rem;color:#cbd5e1"></i>
                </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                <code class="text-primary">{{ $product->code }}</code>
            </div>

            <hr>

            <!-- Info Detail -->
            <dl class="row mb-0" style="font-size:.875rem">
                <dt class="col-5 text-muted">Kategori</dt>
                <dd class="col-7">
                    <span class="badge bg-light text-dark border">
                        {{ $product->category->name }}
                    </span>
                </dd>

                <dt class="col-5 text-muted">Supplier</dt>
                <dd class="col-7">{{ $product->supplier->name ?? '-' }}</dd>

                <dt class="col-5 text-muted">Satuan</dt>
                <dd class="col-7">{{ ucfirst($product->unit) }}</dd>

                <dt class="col-5 text-muted">Harga Beli</dt>
                <dd class="col-7">
                    Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                </dd>

                <dt class="col-5 text-muted">Harga Jual</dt>
                <dd class="col-7 fw-semibold text-success">
                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                </dd>

                <dt class="col-5 text-muted">Margin</dt>
                <dd class="col-7">
                    @php $margin = $product->selling_price - $product->purchase_price; @endphp
                    <span class="badge {{ $margin >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                        Rp {{ number_format($margin, 0, ',', '.') }}
                    </span>
                </dd>

                <dt class="col-5 text-muted">Stok</dt>
                <dd class="col-7">
                    <span class="badge rounded-pill {{ $product->is_low_stock ? 'bg-danger' : 'bg-success' }} fs-6 px-3">
                        {{ $product->stock }} {{ $product->unit }}
                    </span>
                    @if($product->is_low_stock)
                    <div class="text-danger small mt-1">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Stok rendah! Min: {{ $product->min_stock }}
                    </div>
                    @endif
                </dd>

                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7">
                    @if($product->is_active)
                        <span class="badge bg-success rounded-pill">Aktif</span>
                    @else
                        <span class="badge bg-secondary rounded-pill">Nonaktif</span>
                    @endif
                </dd>

                @if($product->description)
                <dt class="col-5 text-muted">Deskripsi</dt>
                <dd class="col-7">{{ $product->description }}</dd>
                @endif

                <dt class="col-5 text-muted">Ditambahkan</dt>
                <dd class="col-7">{{ $product->created_at->format('d M Y') }}</dd>
            </dl>

            <hr>

            <div class="d-flex gap-2">
                <a href="{{ route('products.edit', $product) }}"
                    class="btn btn-warning btn-sm flex-grow-1">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('products.index') }}"
                    class="btn btn-light btn-sm flex-grow-1">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Kanan: Histori Stok -->
    <div class="col-lg-8">

        <!-- Statistik Ringkas -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3" style="background:#dbeafe">
                        <i class="bi bi-bag-fill" style="color:#1d4ed8"></i>
                    </div>
                    <h4 class="fw-bold mb-0">
                        {{ $product->purchaseItems->sum('quantity') }}
                    </h4>
                    <p class="text-muted small mb-0">Total Dibeli</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3" style="background:#d1fae5">
                        <i class="bi bi-cart-fill" style="color:#059669"></i>
                    </div>
                    <h4 class="fw-bold mb-0">
                        {{ $product->saleItems->sum('quantity') }}
                    </h4>
                    <p class="text-muted small mb-0">Total Terjual</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon mb-3" style="background:#fef3c7">
                        <i class="bi bi-box-seam-fill" style="color:#d97706"></i>
                    </div>
                    <h4 class="fw-bold mb-0">{{ $product->stock }}</h4>
                    <p class="text-muted small mb-0">Stok Sekarang</p>
                </div>
            </div>
        </div>

        <!-- Histori Keluar Masuk Stok -->
        <div class="table-card">
            <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2 text-warning"></i>
                    Histori Stok
                </h6>
                <a href="{{ route('stock.index') }}?search={{ $product->name }}"
                    class="btn btn-sm btn-outline-secondary">
                    Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Sebelum</th>
                            <th class="text-center">Perubahan</th>
                            <th class="text-center">Sesudah</th>
                            <th>Keterangan</th>
                            <th>Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->stockAdjustments->take(15) as $adj)
                        <tr>
                            <td style="font-size:.8rem">
                                <div>{{ $adj->created_at->format('d M Y') }}</div>
                                <div class="text-muted">{{ $adj->created_at->format('H:i') }}</div>
                            </td>
                            <td class="text-center">
                                @if($adj->type == 'in')
                                    <span class="badge bg-success rounded-pill">📥 Masuk</span>
                                @elseif($adj->type == 'out')
                                    <span class="badge bg-danger rounded-pill">📤 Keluar</span>
                                @else
                                    <span class="badge bg-warning rounded-pill text-dark">🔧 Opname</span>
                                @endif
                            </td>
                            <td class="text-center text-muted">{{ $adj->quantity_before }}</td>
                            <td class="text-center fw-bold">
                                <span class="{{ $adj->type == 'out' ? 'text-danger' : 'text-success' }}">
                                    {{ $adj->type == 'out' ? '-' : '+' }}{{ $adj->quantity_change }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold">{{ $adj->quantity_after }}</td>
                            <td style="font-size:.8rem">
                                <div>{{ $adj->reason }}</div>
                                @if($adj->reference)
                                <div class="text-muted"><code>{{ $adj->reference }}</code></div>
                                @endif
                            </td>
                            <td style="font-size:.8rem">{{ $adj->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                                Belum ada histori stok
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
