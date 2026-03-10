@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page-title', 'Manajemen Supplier')

@section('breadcrumb')
    <li class="breadcrumb-item active">Supplier</li>
@endsection

@section('content')
<div class="table-card">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Daftar Supplier</h5>
                <p class="text-muted small mb-0">Total {{ $suppliers->total() }} supplier terdaftar</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah Supplier
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('suppliers.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-muted">Cari Supplier</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Nama, telepon, atau contact person...">
                    </div>
                </div>
                <div class="col-md-6 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Cari
                    </button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
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
                    <th>Nama Supplier</th>
                    <th>Contact Person</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th class="text-center">Jml Produk</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                <tr>
                    <td class="text-muted small">{{ $suppliers->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded d-flex align-items-center justify-content-center"
                                style="width:38px;height:38px;background:#ede9fe;flex-shrink:0">
                                <i class="bi bi-truck" style="color:#7c3aed"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $supplier->name }}</div>
                                @if($supplier->address)
                                <div class="text-muted text-truncate" style="font-size:.75rem;max-width:200px">
                                    {{ $supplier->address }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem">{{ $supplier->contact_person ?? '-' }}</td>
                    <td style="font-size:.875rem">{{ $supplier->phone ?? '-' }}</td>
                    <td style="font-size:.875rem">{{ $supplier->email ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary-subtle text-primary border rounded-pill">
                            {{ $supplier->products_count }} produk
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('suppliers.show', $supplier) }}"
                                class="btn btn-sm btn-light" title="Detail">
                                <i class="bi bi-eye text-info"></i>
                            </a>
                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil text-warning"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light"
                                onclick="confirmDelete('{{ route('suppliers.destroy', $supplier) }}', '{{ $supplier->name }}')"
                                title="Hapus">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-truck fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Belum ada data supplier</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($suppliers->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }}
            dari {{ $suppliers->total() }} data
        </div>
        {{ $suppliers->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">🗑️</div>
                <h5 class="fw-bold mb-2">Hapus Supplier?</h5>
                <p class="text-muted mb-4">
                    Supplier <strong id="deleteItemName"></strong> akan dihapus.
                </p>
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
