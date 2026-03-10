@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page-title', 'Manajemen Karyawan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Karyawan</li>
@endsection

@section('content')
<div class="table-card">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Data Karyawan</h5>
                <p class="text-muted small mb-0">Total {{ $employees->total() }} karyawan terdaftar</p>
            </div>
            <div class="d-flex gap-2">
                <!-- Import Excel -->
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload me-1"></i>Import Excel
                </button>
                <!-- Export Excel -->
                <a href="{{ route('employees.export') }}" class="btn btn-outline-info">
                    <i class="bi bi-download me-1"></i>Export Excel
                </a>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Karyawan
                </a>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('employees.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">Cari Karyawan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Nama, kode, atau jabatan...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resign</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
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
                    <th>Kode</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>No. HP</th>
                    <th>Tgl Bergabung</th>
                    <th class="text-end">Gaji Pokok</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $emp)
                <tr>
                    <td class="text-muted small">{{ $employees->firstItem() + $index }}</td>
                    <td><code>{{ $emp->employee_code }}</code></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width:34px;height:34px;font-size:.75rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($emp->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $emp->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem">{{ $emp->position }}</td>
                    <td style="font-size:.875rem">{{ $emp->phone ?? '-' }}</td>
                    <td style="font-size:.875rem">{{ $emp->join_date->format('d M Y') }}</td>
                    <td class="text-end fw-semibold" style="font-size:.875rem">
                        Rp {{ number_format($emp->base_salary, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if($emp->status == 'active')
                            <span class="badge bg-success rounded-pill">Aktif</span>
                        @elseif($emp->status == 'inactive')
                            <span class="badge bg-secondary rounded-pill">Tidak Aktif</span>
                        @else
                            <span class="badge bg-danger rounded-pill">Resign</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil text-warning"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light"
                                onclick="confirmDelete('{{ route('employees.destroy', $emp) }}', '{{ $emp->name }}')"
                                title="Hapus">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Belum ada data karyawan</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($employees->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $employees->firstItem() }}–{{ $employees->lastItem() }} dari {{ $employees->total() }} data
        </div>
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-header border-0 pb-0">
                <h6 class="fw-bold">Import Data Karyawan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle me-1"></i>
                    Format kolom Excel: <strong>nama, jabatan, telepon, alamat, tgl_bergabung, gaji_pokok, tipe_gaji</strong>
                </div>
                <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-upload me-1"></i>Import Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">🗑️</div>
                <h5 class="fw-bold mb-2">Hapus Karyawan?</h5>
                <p class="text-muted mb-4">Data <strong id="deleteItemName"></strong> akan dihapus.</p>
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