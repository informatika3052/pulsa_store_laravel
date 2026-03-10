@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('breadcrumb')
    <li class="breadcrumb-item active">User</li>
@endsection

@section('content')
<div class="table-card">
    <div class="p-4 border-bottom">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1">Daftar User</h5>
                <p class="text-muted small mb-0">Total {{ $users->total() }} user terdaftar</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah User
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="p-4 border-bottom bg-light">
        <form action="{{ route('users.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">Cari User</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Nama atau email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Role</label>
                    <select name="role_id" class="form-select">
                        <option value="">Semua Role</option>
                        @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
                    <th>User</th>
                    <th>Role</th>
                    <th>No. HP</th>
                    <th>Terdaftar</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-muted small">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width:38px;height:38px;font-size:.8rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.875rem">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-role-{{ $user->role->name }} px-2 py-1">
                            {{ $user->role->display_name }}
                        </span>
                    </td>
                    <td style="font-size:.875rem">{{ $user->phone ?? '-' }}</td>
                    <td style="font-size:.875rem">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-center">
                        @if($user->is_active)
                            <span class="badge bg-success rounded-pill">Aktif</span>
                        @else
                            <span class="badge bg-secondary rounded-pill">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-light" title="Edit">
                                <i class="bi bi-pencil text-warning"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <button type="button" class="btn btn-sm btn-light"
                                onclick="confirmDelete('{{ route('users.destroy', $user) }}', '{{ $user->name }}')"
                                title="Hapus">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted">Belum ada user terdaftar</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} data
        </div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px">
            <div class="modal-body text-center p-5">
                <div class="mb-3" style="font-size:3rem">🗑️</div>
                <h5 class="fw-bold mb-2">Hapus User?</h5>
                <p class="text-muted mb-4">User <strong id="deleteItemName"></strong> akan dihapus.</p>
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
