@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok Barang')

@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan Stok</li>
@endsection

@section('content')
<div class="table-card p-4 mb-4">
    <form action="{{ route('reports.stock') }}" method="GET">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="form-check mt-4">
                    <input class="form-check-input" type="checkbox" name="low_stock"
                        id="lowStock" value="1" {{ request('low_stock') ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="lowStock">
                        Tampilkan hanya stok rendah
                    </label>
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0">Data Stok Barang</h6>
        <span class="badge bg-light text-muted border">{{ $products->total() }} produk</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-center">Status Stok</th>
                    <th class="text-end">Nilai Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                <tr class="{{ $product->is_low_stock ? 'table-warning' : '' }}">
                    <td class="text-muted small">{{ $products->firstItem() + $index }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:.875rem">{{ $product->name }}</div>
                        <div class="text-muted" style="font-size:.75rem"><code>{{ $product->code }}</code></div>
                    </td>
                    <td style="font-size:.875rem">{{ $product->category->name }}</td>
                    <td style="font-size:.875rem">{{ $product->supplier->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge {{ $product->is_low_stock ? 'bg-danger' : 'bg-success' }} rounded-pill fs-6 px-3">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td class="text-center text-muted">{{ $product->min_stock }}</td>
                    <td class="text-center">
                        @if($product->stock == 0)
                            <span class="badge bg-danger">Habis</span>
                        @elseif($product->is_low_stock)
                            <span class="badge bg-warning text-dark">Rendah</span>
                        @else
                            <span class="badge bg-success">Normal</span>
                        @endif
                    </td>
                    <td class="text-end" style="font-size:.875rem">
                        Rp {{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Tidak ada data produk</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($products->count() > 0)
            <tfoot>
                <tr style="background:#f8fafc">
                    <td colspan="7" class="text-end fw-bold">Total Nilai Stok:</td>
                    <td class="text-end fw-bold text-primary">
                        Rp {{ number_format($products->sum(fn($p) => $p->stock * $p->purchase_price), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    @if($products->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} data
        </div>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
