@extends('layouts.app')

@section('title', 'Penggajian')
@section('page-title', 'Manajemen Penggajian')

@section('breadcrumb')
    <li class="breadcrumb-item active">Penggajian</li>
@endsection

@section('content')
<div class="table-card">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Data Penggajian</h5>
                <p class="text-muted small mb-0">Total {{ $salaries->total() }} record gaji</p>
            </div>
            <a href="{{ route('salary.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Input Gaji
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('salary.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Cari Karyawan</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control" placeholder="Nama karyawan...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Bulan</label>
                    <select name="month" class="form-select">
                        <option value="">Semua</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                        <option value="{{ $i+1 }}" {{ request('month') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Tahun</label>
                    <select name="year" class="form-select">
                        @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Belum Dibayar</option>
                        <option value="paid"    {{ request('status') == 'paid'    ? 'selected' : '' }}>Sudah Dibayar</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('salary.index') }}" class="btn btn-outline-secondary">
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
                    <th>Karyawan</th>
                    <th>Periode</th>
                    <th class="text-end">Gaji Pokok</th>
                    <th class="text-end">Tunjangan</th>
                    <th class="text-end">Bonus</th>
                    <th class="text-end">Potongan</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $index => $salary)
                <tr>
                    <td class="text-muted small">{{ $salaries->firstItem() + $index }}</td>
                    <td>
                        <div class="fw-semibold" style="font-size:.875rem">{{ $salary->employee->name }}</div>
                        <div class="text-muted" style="font-size:.75rem">{{ $salary->employee->position }}</div>
                    </td>
                    <td style="font-size:.875rem">{{ $salary->month_name }} {{ $salary->year }}</td>
                    <td class="text-end" style="font-size:.875rem">Rp {{ number_format($salary->base_salary, 0, ',', '.') }}</td>
                    <td class="text-end text-success" style="font-size:.875rem">+ Rp {{ number_format($salary->allowance, 0, ',', '.') }}</td>
                    <td class="text-end text-info" style="font-size:.875rem">+ Rp {{ number_format($salary->bonus, 0, ',', '.') }}</td>
                    <td class="text-end text-danger" style="font-size:.875rem">- Rp {{ number_format($salary->deduction, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold" style="font-size:.875rem">Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($salary->status == 'paid')
                            <span class="badge bg-success rounded-pill">Dibayar</span>
                        @else
                            <span class="badge bg-warning rounded-pill">Belum Dibayar</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('salary.print', $salary) }}" target="_blank"
                                class="btn btn-sm btn-light" title="Cetak Slip">
                                <i class="bi bi-printer text-primary"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-light"
                                onclick="confirmDelete('{{ route('salary.destroy', $salary) }}')"
                                title="Hapus">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-5">
                        <i class="bi bi-cash-stack fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Belum ada data penggajian</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($salaries->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $salaries->firstItem() }}–{{ $salaries->lastItem() }} dari {{ $salaries->total() }} data
        </div>
        {{ $salaries->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">🗑️</div>
                <h5 class="fw-bold mb-2">Hapus Data Gaji?</h5>
                <p class="text-muted mb-4">Data gaji ini akan dihapus permanen.</p>
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
function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
