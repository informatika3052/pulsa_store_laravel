@extends('layouts.app')

@section('title', 'Daftar Barang')
@section('page-title', 'Manajemen Barang')

@section('breadcrumb')
    <li class="breadcrumb-item active">Barang</li>
@endsection

@section('content')
<div class="table-card">
    <!-- Header -->
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Daftar Barang</h5>
                <p class="text-muted small mb-0">
                    Total {{ $products->total() }} barang terdaftar
                </p>
            </div>
            @if(auth()->user()->isAdmin())
            <div class="d-flex gap-2">
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Barang
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('products.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Cari Barang</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Nama atau kode barang...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="">Semua</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="low_stock" id="lowStock"
                            value="1" {{ request('low_stock') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="lowStock">Stok Rendah</label>
                    </div>
                </div>
                <div class="col-md-1 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
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
                    <th width="50">#</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga Beli</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Status</th>
                    @if(auth()->user()->isAdmin())
                    <th class="text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                <tr>
                    <td class="text-muted small">{{ $products->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                style="width:40px;height:40px;object-fit:cover;border-radius:8px">
                            @else
                            <div class="rounded" style="width:40px;height:40px;background:#f1f5f9;display:flex;align-items:center;justify-content:center">
                                <i class="bi bi-box text-muted"></i>
                            </div>
                            @endif
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $product->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">
                                    <code>{{ $product->code }}</code> &bull; {{ $product->unit }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                    </td>
                    <td class="text-end" style="font-size:.875rem">
                        Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                    </td>
                    <td class="text-end fw-semibold" style="font-size:.875rem;color:#059669">
                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($product->is_low_stock)
                            <span class="badge bg-danger rounded-pill">{{ $product->stock }}</span>
                            <div class="text-danger" style="font-size:.65rem">Min: {{ $product->min_stock }}</div>
                        @else
                            <span class="badge bg-success-subtle text-success rounded-pill">{{ $product->stock }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($product->is_active)
                            <span class="badge bg-success rounded-pill">Aktif</span>
                        @else
                            <span class="badge bg-secondary rounded-pill">Nonaktif</span>
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('products.show', $product) }}"
                                class="btn btn-sm btn-light" title="Detail">
                                <i class="bi bi-eye text-info"></i>
                            </a>
                            <a href="{{ route('products.edit', $product) }}"
                                class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil text-warning"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light"
                                onclick="confirmDelete('{{ route('products.destroy', $product) }}', '{{ $product->name }}')"
                                title="Hapus">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-box fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Tidak ada data barang</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} data
        </div>
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">🗑️</div>
                <h5 class="fw-bold mb-2">Hapus Barang?</h5>
                <p class="text-muted mb-4">Barang <strong id="deleteItemName"></strong> akan dihapus (dapat dipulihkan).</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4">Ya, Hapus!</button>
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